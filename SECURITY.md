# Security Policy

## Overview

RelaxCorp is an academic project developed as the final project of the Higher Technician in Network Computer Systems Administration (ASIR) at IES Luis Vives during the 2025–2026 academic year.

The project is provided primarily as a technical portfolio and educational project.

## Supported Versions

Only the current version published in the repository is considered supported.

| Version        | Supported |
| -------------- | --------- |
| Current        | Yes       |
| Older versions | No        |

## Reporting a Vulnerability

If you identify a security vulnerability in RelaxCorp, please report it privately rather than opening a public GitHub issue.

You can report security issues by contacting the project author through the contact information available on the author's GitHub profile.

Please include:

* A clear description of the vulnerability.
* The affected component or file.
* Steps to reproduce the issue.
* The potential security impact.
* Any relevant proof of concept or additional information.

Please do not include real passwords, credentials, API keys, tokens, or other sensitive information in a public issue.

## Security Considerations

RelaxCorp includes several security mechanisms, including:

* Password hashing with PHP's password hashing API.
* Password verification using `password_verify()`.
* Prepared database statements.
* Session-based authentication.
* Session ID regeneration after authentication.
* Role-based access control for administrative functionality.
* Separation of local database credentials from the public repository.

The project also has known security limitations documented in the README, including the absence of HTTPS, login attempt limiting, CSRF protection, and server-side verification of submitted game scores.

## Scope

Security reports should focus on vulnerabilities in the RelaxCorp source code and its documented configuration.

Issues caused exclusively by third-party software, operating systems, web servers, browsers, hosting environments, or external services are outside the direct scope of this project.

Thank you for helping improve the security of the project.
