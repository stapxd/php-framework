<?php

namespace Vendor\Facades;

abstract class Facade {
    static abstract function getFacadeAccessor(): string;
    protected static abstract function getInstance();
}