@extends('layouts/basicLayoutMaster')

<div>
    <h3>{{ $mailData['title'] ?? 'New Company' }}</h3>
    <p>Empresa: {{ $mailData['name'] ?? '-' }}</p>
    <p>Usuario: {{ $mailData['user'] ?? '-' }}</p>
    <p>Password: {{ $mailData['password'] ?? '-' }}</p>
</div>
