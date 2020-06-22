## bogosoft/http-routing

This library contains contracts for abstracting an HTTP request router as a
 PSR-15 middleware component.

Although this library contains minimal implementations, it is intended mainly
to provide implementation-ready contracts.

The focal point of the library--the `Router` class--is a PSR-15 middleware
component with the following responsibilities:

- Resolve an HTTP request to an action, and a sequence of zero or more action filters.
- Invoke the filtered action to obtain an action result.
- Apply the action result to an HTTP response.

#### Requirements

- PHP >=7.4

#### Installation

```bash
composer require bogosoft/http-routing
```
