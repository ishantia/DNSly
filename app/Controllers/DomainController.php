<?php

namespace App\Controllers;

use App\Models\Domain;
use App\Models\Record;

class DomainController {
    
    private function checkAuth() {
        if (!isset($_SESSION['user_id'])) {
            redirect('/login');
        }
        return $_SESSION['user_id'];
    }

    private function logActivity($action, $description) {
        $db = \App\Services\Database::getInstance()->getConnection();
        $stmt = $db->prepare("INSERT INTO activity_logs (user_id, action, description, ip_address) VALUES (?, ?, ?, ?)");
        $stmt->execute([$_SESSION['user_id'], $action, $description, $_SERVER['REMOTE_ADDR'] ?? null]);
    }

    public function index() {
        $user_id = $this->checkAuth();
        $domainModel = new Domain();
        $domains = $domainModel->getAllForUser($user_id);

        view('domains.index', [
            'title' => 'Domains - ' . env('APP_NAME'),
            'domains' => $domains
        ]);
    }

    public function create() {
        $this->checkAuth();
        view('domains.create', [
            'title' => 'Create Domain - ' . env('APP_NAME')
        ]);
    }

    public function store() {
        $user_id = $this->checkAuth();
        $domain_name = $_POST['domain'] ?? '';
        $domain_name = strtolower(trim($domain_name));
        $error = '';

        if (empty($domain_name)) {
            $error = 'Domain name is required.';
        } elseif (!preg_match('/^(?:[-A-Za-z0-9]+\.)+[A-Za-z]{2,6}$/', $domain_name)) {
            $error = 'Invalid domain name format.';
        } else {
            $domainModel = new Domain();
            if ($domainModel->create($user_id, $domain_name)) {
                $this->logActivity('create_domain', "Created domain {$domain_name}");
                redirect('/domains');
            } else {
                $error = 'Failed to create domain.';
            }
        }

        view('domains.create', [
            'title' => 'Create Domain - ' . env('APP_NAME'),
            'error' => $error,
            'domain' => $domain_name
        ]);
    }

    public function show($id) {
        $user_id = $this->checkAuth();
        $domainModel = new Domain();
        $domain = $domainModel->findByIdAndUser($id, $user_id);

        if (!$domain) {
            redirect('/domains');
        }

        // We will fetch records here later
        $recordModel = new Record();
        // $records = $recordModel->getAllForDomain($domain['id']);
        $records = []; 

        view('domains.show', [
            'title' => $domain['domain'] . ' - ' . env('APP_NAME'),
            'domain' => $domain,
            'records' => $records
        ]);
    }

    public function delete($id) {
        $user_id = $this->checkAuth();
        $domainModel = new Domain();
        $domain = $domainModel->findByIdAndUser($id, $user_id);

        if ($domain) {
            if ($domainModel->delete($id, $user_id)) {
                $this->logActivity('delete_domain', "Deleted domain {$domain['domain']}");
            }
        }
        redirect('/domains');
    }
}
