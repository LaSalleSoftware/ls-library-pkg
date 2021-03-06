<?php

/**
 * This file is part of the Lasalle Software library package. 
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright  (c) 2019-2020 The South LaSalle Trading Corporation
 * @license    http://opensource.org/licenses/MIT
 * @author     Bob Bloom
 * @email      bob.bloom@lasallesoftware.ca
 * @link       https://lasallesoftware.ca
 * @link       https://packagist.org/packages/lasallesoftware/ls-library-pkg
 * @link       https://github.com/LaSalleSoftware/ls-library-pkg
 *
 */

namespace Lasallesoftware\Library\Testing\Concerns\Uuid;

// LaSalle Software
use Lasallesoftware\Library\UniversallyUniqueIDentifiers\UuidGenerator;

trait InteractsWithUuid
{
    /**
     * The UuidGenerator instance
     *
     * @var Lasallesoftware\Library\UniversallyUniqueIDentifiers\UuidGenerator
     */
    protected $uuidGenerator;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function makeUuidgenerator()
    {
        $this->uuidGenerator = \App::make(UuidGenerator::class);
    }

    /**
     * Create a UUID
     *
     * @return void
     */
    public function createUuid()
    {
        $this->uuidGenerator->createUuid(3, "from InteractsWithUuid::createUuid()");
        return $this;
    }
}
