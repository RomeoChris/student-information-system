<?php

namespace App\Core\App;


use App\Core\Authentication\Authenticator;
use App\Core\Configuration\Configuration;
use App\Core\Database\Database;
use App\Core\Profile\IUser;
use App\Core\Profile\Profile;
use App\Core\Profile\StudentProfile;
use App\Core\Request\IRequest;
use App\Core\Request\RequestData;
use App\Core\Server\Server;
use App\Core\Session\Session;

interface IApp
{
    public function getRequest(): IRequest;
    public function getSession(): Session;
    public function getConfiguration(): Configuration;
    public function getDatabase(): Database;
    public function getServer(): Server;
    public function getRootPath(): string;
    public function getTemplatesPath(): string;
    public function getIncludesPath(): string;
    public function getSourcePath(): string;
    public function getControllerPath(): string;
    public function getWebPaths(): RequestData;
    public function getDefinitions(): array;
    public function getStudentProfile(): StudentProfile;
    public function getProfile(): Profile;
    public function getUser(): IUser;
    public function getAuthenticator(): Authenticator;
}
