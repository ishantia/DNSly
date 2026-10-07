<?php

namespace App\Controllers;

use App\Models\Domain;
use App\Models\Record;

class RecordController {
    
    private function checkAuth() {
        if (!isset($_SESSION['user_id'])) {
            redirect('/login');
        }
        return $_SESSION['user_id'];
    }

    private function getDomain($domain_id, $user_id) {
        $domainModel = new Domain();
        $domain = $domainModel->findByIdAndUser($domain_id, $user_id);
        if (!$domain) {
            redirect('/domains');
        }
        return $domain;
    }

    private function logActivity($action, $description) {
        $db = \App\Services\Database::getInstance()->getConnection();
        $stmt = $db->prepare("INSERT INTO activity_logs (user_id, action, description, ip_address) VALUES (?, ?, ?, ?)");
        $stmt->execute([$_SESSION['user_id'], $action, $description, $_SERVER['REMOTE_ADDR'] ?? null]);
    }

    public function create($domain_id) {
        $user_id = $this->checkAuth();
        $domain = $this->getDomain($domain_id, $user_id);

        view('records.create', [
            'title' => 'Add Record - ' . $domain['domain'],
            'domain' => $domain
        ]);
    }

    public function store($domain_id) {
        $user_id = $this->checkAuth();
        $domain = $this->getDomain($domain_id, $user_id);

        $type = strtoupper(trim($_POST['type'] ?? ''));
        $name = trim($_POST['name'] ?? '');
        $value = trim($_POST['value'] ?? '');
        $ttl = (int)($_POST['ttl'] ?? 3600);
        
        $error = '';

        if (empty($type) || empty($name) || empty($value)) {
            $error = 'All fields except TTL are required.';
        } elseif (!in_array($type, ['A', 'AAAA', 'CNAME', 'MX', 'TXT', 'NS'])) {
            $error = 'Invalid record type.';
        } else {
            $recordModel = new Record();
            if ($recordModel->create($domain_id, $type, $name, $value, $ttl)) {
                $this->logActivity('create_record', "Added {$type} record to {$domain['domain']}");
                redirect("/domains/{$domain_id}");
            } else {
                $error = 'Failed to create record.';
            }
        }

        view('records.create', [
            'title' => 'Add Record - ' . $domain['domain'],
            'domain' => $domain,
            'error' => $error,
            'record' => compact('type', 'name', 'value', 'ttl')
        ]);
    }

    public function edit($domain_id, $id) {
        $user_id = $this->checkAuth();
        $domain = $this->getDomain($domain_id, $user_id);
        
        $recordModel = new Record();
        $record = $recordModel->findById($id, $domain_id);
        if (!$record) {
            redirect("/domains/{$domain_id}");
        }

        view('records.edit', [
            'title' => 'Edit Record - ' . $domain['domain'],
            'domain' => $domain,
            'record' => $record
        ]);
    }

    public function update($domain_id, $id) {
        $user_id = $this->checkAuth();
        $domain = $this->getDomain($domain_id, $user_id);

        $recordModel = new Record();
        $record = $recordModel->findById($id, $domain_id);
        if (!$record) {
            redirect("/domains/{$domain_id}");
        }

        $type = strtoupper(trim($_POST['type'] ?? ''));
        $name = trim($_POST['name'] ?? '');
        $value = trim($_POST['value'] ?? '');
        $ttl = (int)($_POST['ttl'] ?? 3600);
        
        $error = '';

        if (empty($type) || empty($name) || empty($value)) {
            $error = 'All fields except TTL are required.';
        } elseif (!in_array($type, ['A', 'AAAA', 'CNAME', 'MX', 'TXT', 'NS'])) {
            $error = 'Invalid record type.';
        } else {
            if ($recordModel->update($id, $domain_id, $type, $name, $value, $ttl)) {
                $this->logActivity('update_record', "Updated {$type} record in {$domain['domain']}");
                redirect("/domains/{$domain_id}");
            } else {
                $error = 'Failed to update record.';
            }
        }

        view('records.edit', [
            'title' => 'Edit Record - ' . $domain['domain'],
            'domain' => $domain,
            'error' => $error,
            'record' => array_merge($record, compact('type', 'name', 'value', 'ttl'))
        ]);
    }

    public function delete($domain_id, $id) {
        $user_id = $this->checkAuth();
        $domain = $this->getDomain($domain_id, $user_id);

        $recordModel = new Record();
        $record = $recordModel->findById($id, $domain_id);
        
        if ($record) {
            if ($recordModel->delete($id, $domain_id)) {
                $this->logActivity('delete_record', "Deleted {$record['type']} record from {$domain['domain']}");
            }
        }
        
        redirect("/domains/{$domain_id}");
    }
}
