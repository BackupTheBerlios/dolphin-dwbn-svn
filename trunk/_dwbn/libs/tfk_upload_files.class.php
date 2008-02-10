<?php
/**
 * HTTP_Upload
 *
 * To handle file uploads - much easier, more transparent code!
 */
require_once 'HTTP/Upload.php';

/**
 * MDB2
 *
 * To handle db calls, including sequences.
 */
require_once 'MDB2.php';

/**
 * tfk_upload_files
 *
 * @author Till Klampaeckel <till@php.net>
 */
final class tfk_upload_files
{
    private $_store;
    private $file;

    private function __construct()
    {
    }

    public function __destruct()
    {
        if (!MDB2::isError($this->_store['db'])) {
            $this->_store['db']->disconnect();
        }
        unset($this->_store);
    }

    static function factory($fileVault, $groupID, $memberID, $formName = 'file')
    {
        global $MySQL;

        $cls = new tfk_upload_files;

        $cls->groupID   = $groupID;
        $cls->formName  = $formName;
        $cls->fileVault = realpath($fileVault);
        $cls->member    = $memberID;

        $dsn = sprintf('mysql://%s:%s@%s/%s',
            $MySQL->user,
            $MySQL->passwd,
            $MySQL->host,
            $MySQL->db);
        $cls->db = MDB2::connect($dsn);

        if (MDB2::isError($cls->db)) {
            throw new Exception($cls->db->getDebugInfo(),
                $cls->db->getCode());
        }
        return $cls;
    }

    public function __set($var, $value)
    {
        return $this->_store[$var] = $value;
    }

    public function __get($var)
    {
        return $this->_store[$var];
    }

    public function handle($type)
    {
        $this->_setType($type);

        // handle file upload
        $upload     = new HTTP_Upload('en');
        $this->file = $upload->getFiles($this->_store['formName']);

        if (PEAR::isError($this->file)) {
            throw new Exception($this->file->getMessage(),
                $this->file->getCode());
        }

        if ($this->file->isMissing()) {
            throw new Exception('Please select a file!', 404);
        }

        if ($this->file->isValid()) {

            $this->_store['seed'] = substr(md5(time()), 0, 10);

            $this->_saveToDb();

            $name = $this->_createName();
            
            //throw new Exception($name);

            $this->file->setName($name);
            $moved = $this->file->moveTo($this->_store['fileVault']);

            if (!PEAR::isError($moved)) {
                return true;
            }

            $msg = $moved->getMessage();

            throw new Exception('We could not save the file: ' . $msg, 500);
        }
    }

    private function _createName()
    {
        return sprintf('%s_%s_%s.%s',
            $this->_store['groupID'],
            $this->_store['imageID'],
            $this->_store['seed'],
            $this->file->getProp('ext')
        );
    }

    private function _saveToDb()
    {
        $db = $this->_store['db'];

        $this->_store['imageID'] = $db->nextId('tfk_files');
        if (MDB2::isError($this->_store['imageID'])) {
            throw new Exception($this->_store['imageID']->getMessage(),
                $this->_store['imageID']->getCode());
        }
        
        $query  = "INSERT INTO tfk_files SET";
        $query .= " id = " . (int) $this->_store['imageID'];
        $query .= ", path = " . $db->quote($this->_store['fileVault']);
        $query .= ", realname = " . $db->quote($this->file->getProp('real'));
        $query .= ", extension = " . $db->quote($this->file->getProp('ext'));
        $query .= ", parent_id = " . (int) $this->_store['groupID'];
        $query .= ", parent_type = " . $db->quote($this->_store['type']);
        $query .= ", member_id = " . (int) $this->_store['member'];
        $query .= ", seed = " . $db->quote($this->_store['seed']);
        $query .= ", rec_dateadd = NOW()";

        $status = $db->query($query);
        if (MDB2::isError($status)) {
            throw new Exception($status->getDebugInfo(), $status->getCode());
        }
        return true;
    }

    private function _setType($type)
    {
        if (!in_array($type, array('group', 'forum'))) {
            throw new Exception('Unsupported environment.', 666);
        }
        $this->__set('type', $type);
    }

    static function error($_page, $_page_cont, $type, $msg, $_ni)
    {
        switch ($type) {
        case 'gallery':
            $_page['header']      = _t("_Upload to group gallery error");
            $_page['header_text'] = _t("_Upload to group gallery error");

            $_page_cont[$_ni]['page_main_code'] = _t($msg);
            break;
        }
        return array('_page' => $_page, '_page_cont' => $_page_cont);
    }
}
