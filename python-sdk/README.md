# Inlomax Python SDK

The official Python SDK for the [Inlomax API](https://inlomax.com/docs). This SDK makes it easy for developers to integrate Inlomax services like Airtime, Data, Cable TV, Electricity, Education Pins, and KYC verifications into their Python applications.

## Requirements

- Python >= 3.7
- `requests` library

## Installation

You can install the package via pip:

```bash
pip install inlomax
```

*(Note: Until published on PyPI, you can install it locally by running `pip install .` in this directory).*

## Usage

### Initialization

Initialize the client with your API key. You can find your API key in your Inlomax developer dashboard.

```python
from inlomax import Client, InlomaxError

# Provide your API Key. Set is_sandbox to True if you are using the sandbox environment.
api_key = 'YOUR_API_KEY'
inlomax_client = Client(api_key, is_sandbox=False)
```

### Get Wallet Balance

```python
try:
    response = inlomax_client.get_balance()
    print(response)
except InlomaxError as e:
    print(f"Error: {e}")
```

### Buy Airtime

```python
import uuid

try:
    payload = {
        "serviceID": "100", # Check services endpoint for IDs
        "amount": 200,
        "mobileNumber": "0903837261",
        "request-id": str(uuid.uuid4()) # Ensure this is unique per transaction
    }
    response = inlomax_client.buy_airtime(payload)
    print(response)
except InlomaxError as e:
    print(f"Error: {e}")
```

### Buy Data

```python
import uuid

try:
    payload = {
        "serviceID": "100",
        "mobileNumber": "0903837261",
        "request-id": str(uuid.uuid4())
    }
    response = inlomax_client.buy_data(payload)
    print(response)
except InlomaxError as e:
    print(f"Error: {e}")
```

### Pay for Electricity

```python
import uuid

try:
    payload = {
        "serviceID": "1",
        "amount": 1000,
        "meterNumber": "11111111111", # Example field, check API docs for exact fields required
        "request-id": str(uuid.uuid4())
    }
    response = inlomax_client.buy_electricity(payload)
    print(response)
except InlomaxError as e:
    print(f"Error: {e}")
```

### Pay for Cable TV

```python
import uuid

try:
    payload = {
        "serviceID": "2", # Check API docs for exact IDs
        "smartCardNumber": "1234567890", # Example field, check API docs
        "request-id": str(uuid.uuid4())
    }
    response = inlomax_client.buy_cable(payload)
    print(response)
except InlomaxError as e:
    print(f"Error: {e}")
```

### Buy Education Pins

```python
import uuid

try:
    payload = {
        "serviceID": "3", # Check API docs for exact IDs
        "quantity": 1, # Example field, check API docs
        "request-id": str(uuid.uuid4())
    }
    response = inlomax_client.buy_education_pins(payload)
    print(response)
except InlomaxError as e:
    print(f"Error: {e}")
```

### Verify Meter Number

```python
try:
    payload = {
        "meterNumber": "11111111111",
        "disco": "ikeja", # Example disco, check API docs
        "meterType": "prepaid"
    }
    response = inlomax_client.verify_meter(payload)
    print(response)
except InlomaxError as e:
    print(f"Error: {e}")
```

### Verify Cable IUC

```python
try:
    payload = {
        "smartCardNumber": "1234567890",
        "provider": "dstv" # Example provider, check API docs
    }
    response = inlomax_client.verify_iuc(payload)
    print(response)
except InlomaxError as e:
    print(f"Error: {e}")
```

### Get Services

```python
try:
    response = inlomax_client.get_services()
    print(response)
except InlomaxError as e:
    print(f"Error: {e}")
```

### Verify Bank Account

```python
try:
    payload = {
        "accountNumber": "0123456789",
        "bankCode": "033" # Check API docs for bank codes
    }
    response = inlomax_client.verify_bank_account(payload)
    print(response)
except InlomaxError as e:
    print(f"Error: {e}")
```

### Verify BVN

```python
try:
    payload = {
        "bvn": "12345678901"
    }
    response = inlomax_client.verify_bvn(payload)
    print(response)
except InlomaxError as e:
    print(f"Error: {e}")
```

### Verify NIN

```python
try:
    payload = {
        "nin": "12345678901"
    }
    response = inlomax_client.verify_nin(payload)
    print(response)
except InlomaxError as e:
    print(f"Error: {e}")
```

### Get Transaction

```python
try:
    reference = "your_transaction_reference"
    response = inlomax_client.get_transaction(reference)
    print(response)
except InlomaxError as e:
    print(f"Error: {e}")
```

## Available Methods

- `get_balance()`
- `get_services()`
- `buy_airtime(payload: dict)`
- `buy_data(payload: dict)`
- `buy_cable(payload: dict)`
- `buy_electricity(payload: dict)`
- `buy_education_pins(payload: dict)`
- `verify_bank_account(payload: dict)`
- `verify_bvn(payload: dict)`
- `verify_nin(payload: dict)`
- `verify_iuc(payload: dict)`
- `verify_meter(payload: dict)`
- `get_transaction(reference: str)`

## Support

For any difficulty integrating the API, please refer to the [official documentation](https://inlomax.com/docs) or contact support.

## License

The MIT License (MIT). Please see [LICENSE](LICENSE) for more information.
