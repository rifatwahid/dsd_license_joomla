<?php 

defined('_JEXEC') or die('Restricted access');

class JshoppingModelOrderItemsNativeUploadsFilesAdmin extends JModelLegacy 
{
    const TABLE_NAME = '#__jshopping_order_items_native_uploads_files';
    const PATH_TO_UPLOADS = JPATH_ROOT . '/components/com_jshopping/files/files_upload/';

    public function deleteUploadedFileFromDbAndFileById($id)
    {
        $result = false;

        if (!empty($id)) {
            $uploadedFile = $this->getById($id);

            if (!empty($uploadedFile->file)) {
                $isDeletedFromDb = $this->deleteById($id);

                if ($isDeletedFromDb) {
                    $this->deleteUploadedFileByName($uploadedFile->file);
                    $this->deleteUploadedFileByName($uploadedFile->preview);
                    $result = true;
                }
            }
        }

        return $result;
    }

    public function getById($id)
    {
        if (!empty($id)) {
            $db = \JFactory::getDBO();
            $sqlSelect = 'SELECT * FROM `' . self::TABLE_NAME . '` WHERE `id` = ' . $id;
            $db->setQuery($sqlSelect);
            $result = $db->loadObject();

            return $result;
        }
    }

    public function deleteById($id)
    {
        $result = false;

        if (!empty($id)) {
            $db = \JFactory::getDBO();
            $sqlSelect = 'DELETE FROM `' . self::TABLE_NAME . '` WHERE `id` = ' . $id;
            $db->setQuery($sqlSelect);
            $result = $db->execute();
        }

        return $result;
    }

    public function deleteUploadedFileByName($fileName)
    {
        $result = false;

        if (!empty($fileName)) {
            $fullPathToFile = self::PATH_TO_UPLOADS . $fileName;

            if (file_exists($fullPathToFile)) {
                \JFile::delete($fullPathToFile);
                $result = true;
            }
        }

        return $result;
    }

    public function update($id, $data)
    {
        if (!empty($id) && !empty($data)) {
            $setValues = [];
            $db = \JFactory::getDBO();

            foreach ($data as $columnName => $columnValue) {
                if (isset($columnValue) && !empty($columnName)) {
                    $setValues[] = $db->qn($columnName) . ' = ' . $db->q($columnValue);
                }
            }

            if (!empty($setValues)) {
                $setValues = implode(', ', $setValues);
                $queryUpdate = 'UPDATE ' . $db->qn(self::TABLE_NAME) . ' SET ' . $setValues . ' WHERE `id` = ' . $db->q($id);

                $db->setQuery($queryUpdate);
                $db->execute();
            }
        }
    }

    public function updateFromForm($formData)
    {
        if (!empty($formData)) {
            foreach ($formData as $id => $data) {
                if (!empty($data['qty'])) {
                    $result = [];

                    foreach ($data as $key => $arrayValue) {
                        $result[$key] = $arrayValue[0];
                    }

                    $this->update($id, $result);
                }

            }
        }
    }
}