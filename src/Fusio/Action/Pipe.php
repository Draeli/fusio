<?php
/*
 * Fusio
 * A web-application to create dynamically RESTful APIs
 * 
 * Copyright (C) 2015 Christoph Kappestein <k42b3.x@gmail.com>
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

namespace Fusio\Action;

use Doctrine\DBAL\Connection;
use Fusio\ActionInterface;
use Fusio\ConfigurationException;
use Fusio\Parameters;
use Fusio\Body;
use Fusio\Form;
use Fusio\Form\Element;
use PSX\Util\CurveArray;

/**
 * Pipe
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/gpl-3.0
 * @link    http://fusio-project.org
 */
class Pipe implements ActionInterface
{
	/**
	 * @Inject
	 * @var Doctrine\DBAL\Connection
	 */
	protected $connection;

	/**
	 * @Inject
	 * @var Fusio\Executor
	 */
	protected $executor;

	public function getName()
	{
		return 'Pipe';
	}

	public function handle(Parameters $parameters, Body $data, Parameters $configuration)
	{
		$response = $this->executor->execute($configuration->get('source'), $parameters, $data);
		$data     = Body::fromArray($response);

		return $this->executor->execute($configuration->get('destination'), $parameters, $data);
	}

	public function getForm()
	{
		$form = new Form\Container();
		$form->add(new Element\Action('source', 'Source', $this->connection));
		$form->add(new Element\Action('destination', 'Destination', $this->connection));

		return $form;
	}
}