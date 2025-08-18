<?php

/**
 * PHPStan configuration helper for Pest tests
 * This file helps PHPStan understand that $this is available in Pest tests
 */

namespace {
    if (! function_exists('test')) {
        /**
         * @template T of \PHPUnit\Framework\TestCase
         *
         * @param  \Closure(): void  $closure
         * @return T
         */
        function test(string $description, ?\Closure $closure = null)
        {
            return new class extends \Tests\TestCase {};
        }
    }

    if (! function_exists('it')) {
        /**
         * @template T of \PHPUnit\Framework\TestCase
         *
         * @param  \Closure(): void  $closure
         * @return T
         */
        function it(string $description, ?\Closure $closure = null)
        {
            return new class extends \Tests\TestCase {};
        }
    }

    if (! function_exists('expect')) {
        /**
         * @template T
         *
         * @param  T  $value
         * @return \Pest\Expectation<T>
         */
        function expect($value)
        {
            return new \Pest\Expectation($value);
        }
    }

    if (! function_exists('uses')) {
        /**
         * @param  string  ...$traits
         */
        function uses(...$traits): void {}
    }
}

namespace Pest {
    /**
     * @template T
     */
    class Expectation
    {
        /**
         * @param  T  $value
         */
        public function __construct($value) {}

        /**
         * @param  mixed  $expected
         * @return self<T>
         */
        public function toBe($expected): self
        {
            return $this;
        }

        /**
         * @return self<T>
         */
        public function toBeTrue(): self
        {
            return $this;
        }

        /**
         * @return self<T>
         */
        public function toBeFalse(): self
        {
            return $this;
        }

        /**
         * @return self<T>
         */
        public function toBeNull(): self
        {
            return $this;
        }

        /**
         * @param  mixed  $needle
         * @return self<T>
         */
        public function toContain($needle): self
        {
            return $this;
        }

        /**
         * @return self<T>
         */
        public function toHaveCount(int $count): self
        {
            return $this;
        }

        /**
         * @param  mixed  $expected
         * @return self<T>
         */
        public function toEqual($expected): self
        {
            return $this;
        }

        /**
         * @return self<T>
         */
        public function toBeInstanceOf(string $class): self
        {
            return $this;
        }

        /**
         * @return self<T>
         */
        public function not(): self
        {
            return $this;
        }
    }
}
