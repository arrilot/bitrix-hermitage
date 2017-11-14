<?php

namespace Arrilot\BitrixHermitage;

use Bitrix\Main\Application;
use CBitrixComponent;
use CBitrixComponentTemplate;
use CIBlock;
use InvalidArgumentException;

class Action
{
    protected static $panelButtons = [];
    
    protected static $iblockElementArray = [];
    
    protected static $iblockSectionArray = [];
    
    /**
     * Get edit area id for specific type
     *
     * @param CBitrixComponentTemplate $template
     * @param $type
     * @param $element
     * @return string
     */
    public static function getEditArea($template, $type, $element)
    {
        $id = is_numeric($element) ? $element : $element['ID'];
        return $template->GetEditAreaId("{$type}_{$id}");
    }

    /**
     * @param CBitrixComponentTemplate $template
     * @param $element
     */
    public static function editIBlockElement($template, $element)
    {
        if (!$GLOBALS['APPLICATION']->GetShowIncludeAreas()) {
            return;
        }

        if (is_numeric($element)) {
            $element = static::prepareIBlockElementArrayById($element);
        }
        if (!$element["IBLOCK_ID"] || !$element['ID']) {
            throw new InvalidArgumentException('Element must include ID and IBLOCK_ID');
        }

        $buttons = static::getIBlockElementPanelButtons($element);
        $link = $buttons["edit"]["edit_element"]["ACTION_URL"];

        $template->AddEditAction('iblock_element_' . $element['ID'], $link, CIBlock::GetArrayByID($element["IBLOCK_ID"], "ELEMENT_EDIT"));
    }
    
    /**
     * @param CBitrixComponentTemplate $template
     * @param $element
     * @param string $confirm
     */
    public static function deleteIBlockElement($template, $element, $confirm = 'Вы уверены, что хотите удалить элемент?')
    {
        if (!$GLOBALS['APPLICATION']->GetShowIncludeAreas()) {
            return;
        }

        if (is_numeric($element)) {
            $element = static::prepareIBlockElementArrayById($element);
        }

        if (!$element["IBLOCK_ID"] || !$element['ID']) {
            throw new InvalidArgumentException('Element must include ID and IBLOCK_ID');
        }
    
        $buttons = static::getIBlockElementPanelButtons($element);
        $link = $buttons["edit"]["delete_element"]["ACTION_URL"];

        $template->AddDeleteAction('iblock_element_' . $element['ID'], $link, CIBlock::GetArrayByID($element["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => $confirm));
    }

    /**
     * @param CBitrixComponentTemplate $template
     * @param $element
     */
    public static function editAndDeleteIBlockElement($template, $element)
    {
        static::editIBlockElement($template, $element);
        static::deleteIBlockElement($template, $element);
    }

    /**
     * @param CBitrixComponentTemplate $template
     * @param $element
     * @return string
     */
    public static function areaForIBlockElement($template, $element)
    {
        return static::getEditArea($template, 'iblock_element', $element);
    }

    /**
     * @param CBitrixComponentTemplate $template
     * @param $section
     */
    public static function editIBlockSection($template, $section)
    {
        if (!$GLOBALS['APPLICATION']->GetShowIncludeAreas()) {
            return;
        }

        if (is_numeric($section)) {
            $section = static::prepareIBlockSectionArrayById($section);
        }

        if (!$section["IBLOCK_ID"] || !$section['ID']) {
            throw new InvalidArgumentException('Section must include ID and IBLOCK_ID');
        }
    
        $buttons = static::getIBlockSectionPanelButtons($section);
        $link = $buttons["edit"]["edit_section"]["ACTION_URL"];

        $template->AddEditAction('iblock_section_' . $section['ID'], $link, CIBlock::GetArrayByID($section["IBLOCK_ID"], "SECTION_EDIT"));
    }

    /**
     * @param CBitrixComponentTemplate $template
     * @param $section
     * @param string $confirm
     */
    public static function deleteIBlockSection($template, $section, $confirm = 'Вы уверены, что хотите удалить раздел?')
    {
        if (!$GLOBALS['APPLICATION']->GetShowIncludeAreas()) {
            return;
        }

        if (is_numeric($section)) {
            $section = static::prepareIBlockSectionArrayById($section);
        }

        if (!$section["IBLOCK_ID"] || !$section['ID']) {
            throw new InvalidArgumentException('Section must include ID and IBLOCK_ID');
        }
    
        $buttons = static::getIBlockSectionPanelButtons($section);
        $link = $buttons["edit"]["delete_section"]["ACTION_URL"];

        $template->AddDeleteAction('iblock_section_' . $section['ID'], $link, CIBlock::GetArrayByID($section["IBLOCK_ID"], "SECTION_DELETE"), array("CONFIRM" => $confirm));
    }

    /**
     * @param CBitrixComponentTemplate $template
     * @param $section
     */
    public static function editAndDeleteIBlockSection($template, $section)
    {
        static::editIBlockSection($template, $section);
        static::deleteIBlockSection($template, $section);
    }

    /**
     * @param CBitrixComponentTemplate $template
     * @param $section
     * @return string
     */
    public static function areaForIBlockSection($template, $section)
    {
        return static::getEditArea($template, 'iblock_section', $section);
    }

    /**
     * @param CBitrixComponentTemplate $template
     * @param $element
     * @param string $label
     */
    public static function editHLBlockElement($template, $element, $label = 'Изменить элемент')
    {
        if (!$GLOBALS['APPLICATION']->GetShowIncludeAreas()) {
            return;
        }

        if (!$element["HLBLOCK_ID"] || !$element['ID']) {
            throw new InvalidArgumentException('Element must include ID and HLBLOCK_ID');
        }

        $linkTemplate = '/bitrix/admin/highloadblock_row_edit.php?ENTITY_ID=%s&ID=%s&lang=ru&bxpublic=Y';
        $link = sprintf($linkTemplate, (int) $element["HLBLOCK_ID"], (int) $element["ID"]);

        $template->AddEditAction('hlblock_element_' . $element['ID'], $link, $label);
    }
    
    /**
     * @param CBitrixComponentTemplate $template
     * @param $element
     * @param string $label
     * @param string $confirm
     */
    public static function deleteHLBlockElement($template, $element, $label = 'Удалить элемент', $confirm = 'Вы уверены, что хотите удалить элемент?')
    {
        if (!$GLOBALS['APPLICATION']->GetShowIncludeAreas()) {
            return;
        }
        
        if (!$element["HLBLOCK_ID"] || !$element['ID']) {
            throw new InvalidArgumentException('Element must include ID and HLBLOCK_ID');
        }

        $linkTemplate = '/bitrix/admin/highloadblock_row_edit.php?action=delete&ENTITY_ID=%s&ID=%s&lang=ru';
        $link = sprintf($linkTemplate, (int) $element["HLBLOCK_ID"], (int) $element["ID"]);
    
        $template->AddDeleteAction('hlblock_element_' . $element['ID'], $link, $label, array("CONFIRM" => $confirm));
    }

    /**
     * @param CBitrixComponentTemplate $template
     * @param $element
     */
    public static function editAndDeleteHLBlockElement($template, $element)
    {
        static::editHLBlockElement($template, $element);
        static::deleteHLBlockElement($template, $element);
    }

    /**
     * @param CBitrixComponentTemplate $template
     * @param $element
     * @return string
     */
    public static function areaForHLBlockElement($template, $element)
    {
        return static::getEditArea($template, 'hlblock_element', $element);
    }
    
    /**
     * @param CBitrixComponent|CBitrixComponentTemplate $componentOrTemplate
     * @param $iblockId
     * @param array $options
     */
    public static function addForIBlock($componentOrTemplate, $iblockId, $options = [])
    {
        if (!$GLOBALS['APPLICATION']->GetShowIncludeAreas()) {
            return;
        }

        if ($componentOrTemplate instanceof CBitrixComponentTemplate) {
            $componentOrTemplate = $componentOrTemplate->__component;
        }

        $buttons = CIBlock::GetPanelButtons($iblockId, 0, 0, $options);
        $menu = CIBlock::GetComponentMenu($GLOBALS['APPLICATION']->GetPublicShowMode(), $buttons);

        $componentOrTemplate->addIncludeAreaIcons($menu);
    }

    /**
     * @param $element
     * @return array
     */
    protected static function getIBlockElementPanelButtons($element)
    {
        if (!isset(static::$panelButtons['iblock_element'][$element['ID']])) {
            static::$panelButtons['iblock_element'][$element['ID']] = CIBlock::GetPanelButtons(
                $element["IBLOCK_ID"],
                $element['ID'],
                0,
                ['SECTION_BUTTONS' => false, 'SESSID' => false]
            );
        }

        return static::$panelButtons['iblock_element'][$element['ID']];
    }

    /**
     * @param $section
     * @return array
     */
    protected static function getIBlockSectionPanelButtons($section)
    {
        if (!isset(static::$panelButtons['iblock_section'][$section['ID']])) {
            static::$panelButtons['iblock_section'][$section['ID']] = CIBlock::GetPanelButtons(
                $section["IBLOCK_ID"],
                0,
                $section['ID'],
                ['SESSID' => false]
            );
        }

        return static::$panelButtons['iblock_section'][$section['ID']];
    }
    
    /**
     * @param int $id
     * @return array
     */
    protected static function prepareIBlockElementArrayById($id)
    {
        $id = (int) $id;
        if (!$id) {
            return [];
        }

        if (empty(static::$iblockElementArray[$id])) {
            $connection = Application::getConnection();
            $el = $connection->query("SELECT ID, IBLOCK_ID FROM b_iblock_element WHERE ID = {$id}")->fetch();
            static::$iblockElementArray[$id] = $el ? $el : [];
        }

        return static::$iblockElementArray[$id];
    }

    /**
     * @param int $id
     * @return array
     */
    protected static function prepareIBlockSectionArrayById($id)
    {
        $id = (int) $id;
        if (!$id) {
            return [];
        }

        if (empty(static::$iblockSectionArray[$id])) {
            $connection = Application::getConnection();
            $el = $connection->query("SELECT ID, IBLOCK_ID FROM b_iblock_section WHERE ID = {$id}")->fetch();
            static::$iblockSectionArray[$id] = $el ? $el : [];
        }

        return static::$iblockSectionArray[$id];
    }
}
