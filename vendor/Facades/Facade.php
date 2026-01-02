<?php

namespace Vendor\Facades;

abstract class Facade {
    public static abstract function getFacadeAccessor(): string;
    protected static abstract function getInstance();
}