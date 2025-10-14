<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class GuestFilter implements FilterInterface
{
    /**
     * Do whatever processing this filter needs to do.
     * This filter is for pages that should only be accessible
     * to guests (non-logged-in users) like login and signup pages.
     *
     * @param RequestInterface $request
     * @param array|null       $arguments
     *
     * @return mixed
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        
        // Check if user is already logged in
        if ($session->get('isLoggedIn')) {
            // Get the redirect URL from session, or default to petshop
            $redirectUrl = $session->get('redirect_url') ?: '/petshop';
            
            // Clear the redirect URL from session
            $session->remove('redirect_url');
            
            // Redirect to the intended page or default page
            return redirect()->to($redirectUrl)->with('msg', 'You are already logged in.');
        }
    }

    /**
     * Allows After filters to inspect and modify the response
     * object as needed. This method does not allow any way
     * to stop execution of other after filters, short of
     * throwing an Exception or Error.
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param array|null        $arguments
     *
     * @return mixed
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Nothing to do here
    }
}
