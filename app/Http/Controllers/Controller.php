<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * Send success response (200 OK)
     *
     * @param Request $request
     * @param string $message
     * @param array $data
     * @return JsonResponse|RedirectResponse
     */
    protected function sendSuccessResponse(Request $request, string $message, array $data = [])
    {
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => $data
            ], 200);
        }

        return redirect()->back()->with([
            'type' => 'toast',
            'status' => 'success',
            'message' => $message,
        ]);
    }

    /**
     * Send created response (201 Created)
     *
     * @param Request $request
     * @param string $message
     * @param array $data
     * @param string|null $redirectRoute
     * @return JsonResponse|RedirectResponse
     */
    protected function sendCreatedResponse(Request $request, string $message, array $data = [], string $redirectRoute = null)
    {
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => $data
            ], 201);
        }

        if ($redirectRoute) {
            return redirect()->route($redirectRoute)->with([
                'type' => 'toast',
                'status' => 'success',
                'message' => $message,
            ]);
        }

        return redirect()->back()->with([
            'type' => 'toast',
            'status' => 'success',
            'message' => $message,
        ]);
    }

    /**
     * Send no content response (204 No Content)
     *
     * @param Request $request
     * @return JsonResponse|RedirectResponse
     */
    protected function sendNoContentResponse(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(null, 204);
        }

        return redirect()->back();
    }

    /**
     * Send bad request response (400 Bad Request)
     *
     * @param Request $request
     * @param string $message
     * @param array $errors
     * @return JsonResponse|RedirectResponse
     */
    protected function sendBadRequestResponse(Request $request, string $message, array $errors = [])
    {
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => $message,
                'errors' => $errors
            ], 400);
        }

        return back()->withErrors($errors)->with([
            'type' => 'toast',
            'status' => 'error',
            'message' => $message,
        ]);
    }

    /**
     * Send unauthorized response (401 Unauthorized)
     *
     * @param Request $request
     * @param string $message
     * @return JsonResponse|RedirectResponse
     */
    protected function sendUnauthorizedResponse(Request $request, string $message = 'Unauthorized')
    {
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => $message
            ], 401);
        }

        return redirect()->route('auth.login')->with([
            'type' => 'toast',
            'status' => 'error',
            'message' => $message,
        ]);
    }

    /**
     * Send forbidden response (403 Forbidden)
     *
     * @param Request $request
     * @param string $message
     * @return JsonResponse|RedirectResponse
     */
    protected function sendForbiddenResponse(Request $request, string $message = 'Forbidden')
    {
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => $message
            ], 403);
        }

        return back()->with([
            'type' => 'toast',
            'status' => 'error',
            'message' => $message,
        ]);
    }

    /**
     * Send not found response (404 Not Found)
     *
     * @param Request $request
     * @param string $message
     * @return JsonResponse|RedirectResponse
     */
    protected function sendNotFoundResponse(Request $request, string $message = 'Resource not found')
    {
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => $message
            ], 404);
        }

        return back()->with([
            'type' => 'toast',
            'status' => 'error',
            'message' => $message,
        ]);
    }

    /**
     * Send validation error response (422 Unprocessable Entity)
     *
     * @param Request $request
     * @param string $message
     * @param array $errors
     * @return JsonResponse|RedirectResponse
     */
    protected function sendValidationErrorResponse(Request $request, string $message, array $errors = [])
    {
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => $message,
                'errors' => $errors
            ], 422);
        }

        return back()->withErrors($errors)->withInput()->with([
            'type' => 'toast',
            'status' => 'error',
            'message' => $message,
        ]);
    }

    /**
     * Send server error response (500 Internal Server Error)
     *
     * @param Request $request
     * @param string $message
     * @param \Throwable|null $exception
     * @return JsonResponse|RedirectResponse
     */
    protected function sendServerErrorResponse(Request $request, string $message = 'Server Error', \Throwable $exception = null)
    {
        if ($exception) {
            report($exception);
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => $message
            ], 500);
        }

        return back()->with([
            'type' => 'toast',
            'status' => 'error',
            'message' => $message,
        ]);
    }

    /**
     * Send failed response (generic error response)
     *
     * @param Request $request
     * @param string $message
     * @param int $statusCode
     * @return JsonResponse|RedirectResponse
     */
    protected function sendFailedResponse(Request $request, string $message, int $statusCode = 401)
    {
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => $message
            ], $statusCode);
        }

        return back()->with([
            'type' => 'toast',
            'status' => 'error',
            'message' => $message,
        ]);
    }

    /**
     * Send too many requests response (429 Too Many Requests)
     *
     * @param Request $request
     * @param string $message
     * @param int $seconds
     * @return JsonResponse|RedirectResponse
     */
    protected function sendTooManyRequestsResponse(Request $request, string $message, int $seconds = 60)
    {
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => $message,
                'retry_after' => $seconds
            ], 429)->header('Retry-After', $seconds);
        }

        return back()->with([
            'type' => 'toast',
            'status' => 'error',
            'message' => $message,
            'retry_after' => $seconds
        ]);
    }

    /**
     * Send conflict response (409 Conflict)
     *
     * @param Request $request
     * @param string $message
     * @return JsonResponse|RedirectResponse
     */
    protected function sendConflictResponse(Request $request, string $message = 'Resource conflict')
    {
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => $message
            ], 409);
        }

        return back()->with([
            'type' => 'toast',
            'status' => 'error',
            'message' => $message,
        ]);
    }
}
