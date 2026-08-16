import requests
from requests.exceptions import RequestException
from .exceptions import InlomaxError

class Client:
    """
    Client for interacting with the Inlomax API.
    """
    def __init__(self, api_key: str, is_sandbox: bool = False):
        """
        Initialize the Inlomax Client.
        
        :param api_key: The Inlomax API Key.
        :param is_sandbox: Set to True to use the sandbox environment.
        """
        self.api_key = api_key
        self.base_url = 'https://inlomax.com/sandbox/' if is_sandbox else 'https://inlomax.com/api/'
        
        self.session = requests.Session()
        self.session.headers.update({
            'Authorization': f'Token {self.api_key}',
            'Content-Type': 'application/json',
            'Accept': 'application/json',
        })
        # Disable SSL verification to match PHP SDK behavior for testing
        self.session.verify = False

    def get_balance(self) -> dict:
        """Get the wallet balance."""
        return self._request('GET', 'balance')

    def get_services(self) -> dict:
        """Get the available services."""
        return self._request('GET', 'services')

    def buy_airtime(self, payload: dict) -> dict:
        """
        Buy Airtime.
        
        :param payload: Must contain serviceID, amount, mobileNumber, request-id
        """
        return self._request('POST', 'airtime', payload)

    def buy_data(self, payload: dict) -> dict:
        """
        Buy Data.
        
        :param payload: Must contain serviceID, mobileNumber, request-id
        """
        return self._request('POST', 'data', payload)

    def buy_cable(self, payload: dict) -> dict:
        """Pay for Utility / Cable."""
        return self._request('POST', 'cable', payload)

    def buy_electricity(self, payload: dict) -> dict:
        """Pay for Electricity."""
        return self._request('POST', 'electricity', payload)

    def buy_education_pins(self, payload: dict) -> dict:
        """Buy Education Pins."""
        return self._request('POST', 'edu', payload)

    def verify_bank_account(self, payload: dict) -> dict:
        """Verify Bank Account."""
        return self._request('POST', 'verifybankacct', payload)

    def verify_bvn(self, payload: dict) -> dict:
        """Verify BVN."""
        return self._request('POST', 'kyc/bvn', payload)

    def verify_nin(self, payload: dict) -> dict:
        """Verify NIN."""
        return self._request('POST', 'kyc/nin', payload)

    def verify_iuc(self, payload: dict) -> dict:
        """Verify IUC Number (Cable)."""
        return self._request('POST', 'verifyiuc', payload)

    def verify_meter(self, payload: dict) -> dict:
        """Verify Meter Number (Electricity)."""
        return self._request('POST', 'verifymeter', payload)

    def get_transaction(self, reference: str) -> dict:
        """Get Transaction Details."""
        return self._request('GET', 'transaction', {'reference': reference})

    def _request(self, method: str, endpoint: str, data: dict = None) -> dict:
        """
        Send an HTTP request to the Inlomax API.
        
        :param method: HTTP Method (GET, POST, etc.)
        :param endpoint: API Endpoint
        :param data: Dictionary of data to send (query params for GET, JSON for others)
        :raises InlomaxError: On network or API errors
        """
        url = f"{self.base_url}{endpoint}"
        
        try:
            kwargs = {}
            if data:
                if method.upper() == 'GET':
                    kwargs['params'] = data
                else:
                    kwargs['json'] = data

            response = self.session.request(method, url, **kwargs)
            
            # The PHP SDK ignores HTTP errors natively and handles them via JSON decoding,
            # but we can check if it's a valid JSON response.
            try:
                decoded = response.json()
            except ValueError:
                raise InlomaxError(f"Failed to decode JSON response: {response.text}", status_code=response.status_code)
                
            if isinstance(decoded, dict) and decoded.get('status') == 'failed':
                message = decoded.get('message', 'Unknown API error')
                raise InlomaxError(message, status_code=response.status_code)

            return decoded

        except RequestException as e:
            raise InlomaxError(f"Network error: {str(e)}", original_exception=e)
