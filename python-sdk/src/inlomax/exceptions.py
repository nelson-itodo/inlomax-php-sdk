class InlomaxError(Exception):
    """
    Base exception for all errors thrown by the Inlomax SDK.
    """
    def __init__(self, message: str, status_code: int = None, original_exception: Exception = None):
        super().__init__(message)
        self.status_code = status_code
        self.original_exception = original_exception
