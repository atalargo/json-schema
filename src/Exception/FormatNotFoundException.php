<?php
/* ===========================================================================
 * Copyright 2014-2017 The Opis Project
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *    http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * ============================================================================ */

namespace Opis\JsonSchema\Exception;

use RuntimeException, Throwable;

class FormatNotFoundException extends RuntimeException
{

    /** @var string */
    protected $type;

    /** @var string */
    protected $format;

    /**
     * @inheritDoc
     */
    public function __construct(string $type, string $format, Throwable $previous = null)
    {
        $this->type = $type;
        $this->format = $format;
        parent::__construct("Format '{$format}' was not found for '{$type}' type", 0, $previous);
    }

    /**
     * @return string
     */
    public function type(): string
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function format(): string
    {
        return $this->format;
    }
}