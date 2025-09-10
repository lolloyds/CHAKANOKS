<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class RoleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $user = session()->get('user');
        $allowedRoles = $arguments ?? [];

        if (!$user) {
            return redirect()->to(base_url('login'));
        }

        if (!empty($allowedRoles) && !in_array($user['role'], $allowedRoles)) {
            // Redirect Branch Managers to their dashboard
            if ($user['role'] === 'Branch Manager') {
                return redirect()->to(base_url('bdashboard'));
            }
            return redirect()->to(base_url('dashboard'));
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
