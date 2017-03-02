<?php

/**
 * Media data access object.
 *
 * @package media2
 * @author Thomas Goetz
 * @author Demian Turner <demian@phpkitchen.com>
 * @author Dmitri Lakachauskis <lakiboy83@gmail.com>
 */
class Media2DAO extends SGL_Manager
{
    /**
     * Returns a singleton Media2DAO instance.
     *
     * @return Media2DAO
     */
    public static function &singleton()
    {
        static $instance;

        // If the instance is not there, create one
        if (!isset($instance)) {
            $class = __CLASS__;
            $instance = new $class();
        }
        return $instance;
    }

    public function getMediaTypeIdByName($typeName)
    {
        $query = "
            SELECT   media_type_id
            FROM     media_type
            WHERE    name = " . $this->dbh->quoteSmart($typeName) . "
        ";
        return $this->dbh->getOne($query);
    }

    public function getMimeTypeInfoList()
    {
        $query = "
            SELECT   media_mime_id, name
            FROM     media_mime
            ORDER BY media_mime_id ASC
        ";
        return $this->dbh->getAssoc($query);
    }

    public function getMediaTypeInfoList()
    {
        $query = "
            SELECT   media_type_id, description
            FROM     media_type
            ORDER BY media_type_id ASC
        ";
        return $this->dbh->getAssoc($query);
    }

    public function getMimeTypes()
    {
        $query = "
            SELECT content_type, ident
            FROM   media_mime
        ";
        return $this->dbh->getAssoc($query);
    }

    public function getMimeTypesByMediaTypeId($typeId)
    {
        $query = "
            SELECT mm.content_type, mm.ident
            FROM   media_mime AS mm, `media_type-mime` mtm
            WHERE  mm.media_mime_id = mtm.media_mime_id
                   AND mtm.media_type_id = " . intval($typeId) . "
        ";
        return $this->dbh->getAssoc($query);
    }

    public function getMimeInfoByMimeType($mimeType)
    {
        $query = "
            SELECT *
            FROM   media_mime
            WHERE  content_type = " . $this->dbh->quoteSmart($mimeType) . "
        ";
        return $this->dbh->getRow($query);
    }

    public function getMediaTypeById($typeId)
    {
        $query = "
            SELECT name
            FROM   media_type
            WHERE  media_type_id = " . intval($typeId) . "
        ";
        return $this->dbh->getOne($query);
    }

    public function getMediaById($mediaId)
    {
        $query = "
            SELECT *
            FROM   media
            WHERE  media_id = " . intval($mediaId) . "
        ";
        return $this->dbh->getRow($query);
    }

    public function getMediaTypes()
    {
        $query = "
            SELECT *
            FROM   media_type
        ";
        return $this->dbh->getAll($query);
    }

    public function addMedia($aFields)
    {
        $aFields['media_id']     = $this->dbh->nextId('media');
        $aFields['date_created'] = SGL_Date::getTime($gmt = true);
        $aFields['last_updated'] = $aFields['date_created'];

        $ok = $this->dbh->autoExecute('media', $aFields, DB_AUTOQUERY_INSERT);
        if (PEAR::isError($ok)) {
            $ret = $ok;
        } else {
            $ret = $aFields['media_id'];
        }
        return $ret;
    }

    public function updateMediaById($mediaId, $aFields)
    {
        $aFields['last_updated'] = SGL_Date::getTime($gmt = true);
        $where = 'media_id = ' . intval($mediaId);
        $ok = $this->dbh->autoExecute('media', $aFields,
            DB_AUTOQUERY_UPDATE, $where);
        return $ok;
    }

    public function deleteMediaById($mediaId)
    {
        $query = "
            DELETE FROM media WHERE media_id = " . intval($mediaId) . "
        ";
        $ok = $this->dbh->query($query);
        return $ok;
    }

    public function getMaxMediaOrderByFkId($fkId)
    {
        $query = "
            SELECT MAX(item_order)
            FROM   media
            WHERE  fk_id = " . intval($fkId) . "
        ";
        return $this->dbh->getOne($query);
    }

    public function linkMediaToFk($mediaId, $fkId, $typeId = null)
    {
        $itemOrder = $this->getMaxMediaOrderByFkId($fkId);
        $aFields   = array(
            'fk_id'      => $fkId,
            'item_order' => $itemOrder + 1
        );
        if (!empty($typeId)) {
            $aFields['media_type_id'] = $typeId;
        }
        return $this->updateMediaById($mediaId, $aFields);
    }

    public function getAssocMediaByEntity($entityName, $entityId)
    {
        $tableName = $entityName . '-media';
        $tableFkId = $entityName . '_id';
        $query     = "
            SELECT media_id
            FROM   " . $this->dbh->quoteIdentifier($tableName) . "
            WHERE  $tableFkId = " . $this->dbh->quoteSmart($entityId) . "
        ";
        return $this->dbh->getOne($query);
    }

    public function assocMediaByEntity($entityName, $entityId, $mediaId)
    {
        $tableName = $entityName . '-media';
        $tableFkId = $entityName . '_id';
        $query = "
            DELETE FROM " . $this->dbh->quoteIdentifier($tableName) . "
            WHERE  $tableFkId = " . $this->dbh->quoteSmart($entityId) . "
        ";
        $ok = $this->dbh->query($query);
        if (!PEAR::isError($ok)) {
            $aFields['media_id'] = $mediaId;
            $aFields[$tableFkId] = $entityId;
            $ok = $this->dbh->autoExecute(
                $this->dbh->quoteIdentifier($tableName),
                $aFields,
                DB_AUTOQUERY_INSERT
            );
        }
        return $ok;
    }

    public function getMedias($typeId = null, $mimeTypeId = null,
        $fkId = null, $limit = null, $offset = 0, $usrId = null)
    {
        if (!empty($typeId)) {
            if (is_array($typeId)) {
                $aConstraints[] = 'm.media_type_id IN (' . implode(', ', $typeId) . ')';
            } else {
                $aConstraints[] = 'm.media_type_id = ' . intval($typeId);
            }
        }
        if (!empty($mimeTypeId)) {
            $aConstraints[] = 'm.media_mime_id = ' . intval($mimeTypeId);
        }
        if (!empty($fkId)) {
            $aConstraints[] = 'm.fk_id = ' . intval($fkId);
        }
        if (!empty($usrId)) {
            $aConstraints[] = 'm.created_by = ' . intval($usrId);
        }
        $constraint = !empty($aConstraints)
            ? ' WHERE ' . implode(' AND ', $aConstraints)
            : '';
        $query = "
            SELECT    m.*, mt.name AS media_type
            FROM      media AS m
                      LEFT JOIN media_type AS mt
                        ON m.media_type_id = mt.media_type_id
                      $constraint
            ORDER BY  m.fk_id DESC, m.item_order ASC, m.date_created DESC
        ";
        if (!empty($limit)) {
            $query = $this->dbh->modifyLimitQuery($query, $offset, $limit);
        }

        return $this->dbh->getAll($query);
    }

    public function getPagedMedias($filter, $pagerOptions = array())
    {
        if (!empty($filter->mediaTypeId)) {
            if (is_array($filter->mediaTypeId)) {
                $aConstraints[] = 'm.media_type_id IN (' . implode(', ', $filter->mediaTypeId) . ')';
            } else {
                $aConstraints[] = 'm.media_type_id = ' . intval($filter->mediaTypeId);
            }
        }
        if (!empty($filter->mimeTypeId)) {
            $aConstraints[] = 'm.media_mime_id = ' . intval($filter->mimeTypeId);
        }
        if (!empty($filter->fkId)) {
            $aConstraints[] = 'm.fk_id = ' . intval($filter->fkId);
        }
        if (!empty($filter->usrId)) {
            $aConstraints[] = 'm.created_by = ' . intval($filter->usrId);
        }
        $constraint = !empty($aConstraints)
            ? ' WHERE ' . implode(' AND ', $aConstraints)
            : '';
        $query = "
            SELECT    m.*, mt.name AS media_type
            FROM      media AS m
                      LEFT JOIN media_type AS mt
                        ON m.media_type_id = mt.media_type_id
                      $constraint
            ORDER BY  m.fk_id DESC, m.item_order ASC, m.date_created DESC
        ";

        return SGL_DB::getPagedData($this->dbh, $query, $pagerOptions, false, DB_FETCHMODE_OBJECT);
    }


    public function getMediaCount($typeId = null, $mimeTypeId = null,
        $fkId = null)
    {
        if (!empty($typeId)) {
            $aConstraints[] = 'media_type_id = ' . intval($typeId);
        }
        if (!empty($mimeTypeId)) {
            $aConstraints[] = 'media_mime_id = ' . intval($mimeTypeId);
        }
        if (!empty($fkId)) {
            $aConstraints[] = 'fk_id = ' . intval($fkId);
        }
        $constraint = !empty($aConstraints)
            ? ' WHERE ' . implode(' AND ', $aConstraints)
            : '';
        $query = "
            SELECT    COUNT(media_id)
            FROM      media
                      $constraint
        ";
        return $this->dbh->getOne($query);
    }
}
?>