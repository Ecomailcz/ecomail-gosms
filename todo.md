### Docs
https://api.gosms.eu/redoc#tag/Messages

### Implemented endpoints (api.gosms.eu)

| Endpoint | Method | Request | Client method | Fixture |
|----------|--------|---------|----------------|---------|
| `/api/v2/auth/token` | POST | AuthenticationRequest | `authenticate()` | authenticate.json, authenticate_validation_error.json |
| `/api/v2/auth/refresh` | POST | RefreshAccessTokenRequest | `refreshToken()` | refresh_token.json, refresh_token_validation_error.json |
| `/api/v2/messages/` | POST | SendMessageAsyncRequest | `sendMessageAsync()` | send_message_async_success.json, send_message_async_validation_error.json, send_message_async_invalid_channel.json |
| `/api/v2/messages/bulk` | POST | SendMessagesAsyncRequest | `sendMessagesAsync()` | send_messages_async.json |
| `/api/v2/messages/by-custom-id/{customId}` | GET | MessageStatusRequest | `getMessageStatistics()` | message_detail.json |
