<?php
/*------------------------------------------------------------------------
# Cobalt
# ------------------------------------------------------------------------
# @author Cobalt
# @copyright Copyright (C) 2012 cobaltcrm.org All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Website: http://www.cobaltcrm.org
-------------------------------------------------------------------------*/
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' ); 

class CobaltViewSourcesHtml extends JViewHtml{

    function render($tpl = null)
    {
        //authenticate the current user to make sure they are an admin
        CobaltHelperUsers::authenticateAdmin();

        //document
        $document = JFactory::getDocument();
        $document->addScript(JURI::base().'libraries/crm/media/js/cobalt-admin.js');

         /** Menu Links **/
        $menu = CobaltHelperMenu::getMenuModules();
        $this->menu = $menu;

        //gather information for view
        $model = new CobaltModelSources();
        $layout = $this->getLayout();
        $model->set("_layout",$layout);
        $this->pagination   = $model->getPagination();
        
        if ( $layout && $layout == 'edit' ){
            
            //toolbar
            CRMToolbarHelper::cancel('cancel');
            CRMToolbarHelper::save('save');
            
            //information for view
            $this->source_types = CobaltHelperDropdown::getSources();
            $this->source = $model->getSource();
            
        }else{
            
            //buttons
            CRMToolbarHelper::addNew('edit');
            CRMToolbarHelper::editList('edit');
            CRMToolbarHelper::deleteList(JText::_('COBALT_CONFIRMATION'),'delete');

            //get sources
            $sources = $model->getSources();
            $this->sources = $sources;
                
            // Initialise state variables.
            $state = $model->getState();
            $this->state = $state;

            $this->listOrder  = $this->state->get('Sources.filter_order');
            $this->listDirn   = $this->state->get('Sources.filter_order_Dir');
            $this->saveOrder  = $this->listOrder == 's.ordering';

        }
        
        //display
        return parent::render();
    }
}