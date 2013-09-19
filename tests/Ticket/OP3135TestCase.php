<?php

/**
 * Doctrine_Ticket_OP3135_TestCase
 *
 * @package     Doctrine
 * @author      Kimura Youichi <kim.upsilon@bucyou.net>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @version     $Revision$
 */
class Doctrine_Ticket_OP3135_TestCase extends Doctrine_UnitTestCase
{
    public function testInit()
    {
        $this->dbh = new Doctrine_Adapter_Mock('mysql');
        $this->conn = Doctrine_Manager::getInstance()->openConnection($this->dbh);
    }

    public function testSubqueryWithInOperatorIsSupported()
    {
        $q = Doctrine_Core::getTable('User')->createQuery('u')
            ->select('u.id')
            ->where('u.id IN (SELECT p.entity_id FROM Phonenumber p WHERE p.id IN ?)', array(array(1, 2)));

        $q->execute();

        $this->assertEqual($this->dbh->pop(), 'SELECT e.id AS e__id FROM entity e WHERE (e.id IN (SELECT p.entity_id AS p__entity_id FROM phonenumber p WHERE (p.id IN (?, ?))) AND (e.type = 0))');
        $this->assertEqual($q->getInternalParams(), array(1, 2));
    }
}
