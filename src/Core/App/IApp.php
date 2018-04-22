<?php

namespace App\Core\App;


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
