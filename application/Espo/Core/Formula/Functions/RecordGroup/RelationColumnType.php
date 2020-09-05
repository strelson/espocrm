<?php
/************************************************************************
 * This file is part of EspoCRM.
 *
 * EspoCRM - Open Source CRM application.
 * Copyright (C) 2014-2020 Yuri Kuznetsov, Taras Machyshyn, Oleksiy Avramenko
 * Website: https://www.espocrm.com
 *
 * EspoCRM is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * EspoCRM is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with EspoCRM. If not, see http://www.gnu.org/licenses/.
 *
 * The interactive user interfaces in modified source and object code versions
 * of this program must display Appropriate Legal Notices, as required under
 * Section 5 of the GNU General Public License version 3.
 *
 * In accordance with Section 7(b) of the GNU General Public License version 3,
 * these Appropriate Legal Notices must retain the display of the "EspoCRM" word.
 ************************************************************************/

namespace Espo\Core\Formula\Functions\RecordGroup;

use Espo\Core\Exceptions\Error;

class RelationColumnType extends \Espo\Core\Formula\Functions\Base
{
    protected function init()
    {
        $this->addDependency('entityManager');
    }

    public function process(\StdClass $item)
    {
        $args = $this->fetchArguments($item);

        if (count($args) < 5) throw new Error("Formula: record\\relationColumn: Not enough arguments.");

        $entityType = $args[0];
        $id = $args[1];
        $link = $args[2];
        $foreignId = $args[3];
        $column = $args[4];

        if (!$entityType) throw new Error("Formula record\\relationColumn: Empty entityType.");
        if (!$id) return null;
        if (!$link) throw new Error("Formula record\\relationColumn: Empty link.");
        if (!$foreignId) return null;
        if (!$column) throw new Error("Formula record\\relationColumn: Empty column.");

        $em = $this->getInjection('entityManager');

        if (!$em->hasRepository($entityType)) throw new Error("Formula: record\\relationColumn: Repository does not exist.");

        $entity = $em->getEntity($entityType, $id);
        if (!$entity) return null;

        return $em->getRepository($entityType)->getRelationColumn($entity, $link, $foreignId, $column);
    }
}
