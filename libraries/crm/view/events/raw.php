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

class CobaltViewEventsRaw extends JViewHtml
{
	function render()
	{
		$app = JFactory::getApplication();

		//grab model
		$model = new CobaltModelEvent();

		if ( $this->getLayout() == "event_listings" || $this->getLayout() == "list" ){

			$events = $model->getEvents();
			$this->events = $events;

		}else{
			//null event
			$event = array();

			$id = null;
			if ( $app->input->get('parent_id') && !$app->input->get('id') ){
				$id = $app->input->get('parent_id');
			}else{
				$id = $app->input->get('id');
			}
			
			//grab event
			if ( $id != null ){
				$event = $model->getEvent($id);
			}



			//pass reference
			$this->event = $event;
		}
		
		if ( $app->input->get('association_id') ){
			$this->association_name = CobaltHelperCobalt::getAssociationName();
		}
		
		//display
		echo parent::render();
	}
	
}