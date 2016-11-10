<?php
/**
 * This file was generated by the ConvertToLegacy class in bronto-legacy.
 * The purpose of the conversion was to maintain PSR-0 compliance while
 * the main development focuses on modern styles found in PSR-4.
 *
 * For the original:
 * @see src/Bronto/Magento/Email/Event/Source.php
 */

class Brontosoftware_Magento_Email_Event_Source implements Brontosoftware_Magento_Connector_Event_SourceInterface
{
    /**
     * @see parent
     */
    public function getEventType()
    {
        return 'message';
    }

    /**
     * @see parent
     */
    public function action($object)
    {
        return 'update';
    }

    /**
     * @see parent
     */
    public function transform($object)
    {
        $template = $object->getTemplate();
        $message = $object->getMessage();
        return array(
            'id' => $template['messageId'],
            'context' => array(
                'fields' => $message['fields']
            ),
            'content' => array(
                array(
                    'type' => 'text',
                    'subject' => strip_tags($message['subject']),
                    'content' => strip_tags($message['content'])
                ),
                array(
                    'type' => 'html',
                    'subject' => $message['subject'],
                    'content' => $message['content']
                )
            )
       );
    }
}