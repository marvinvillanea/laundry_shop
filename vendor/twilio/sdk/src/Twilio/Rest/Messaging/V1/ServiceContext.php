<?php

/**
 * This code was generated by
 * \ / _    _  _|   _  _
 * | (_)\/(_)(_|\/| |(/_  v1.0.0
 * /       /
 */

namespace Twilio\Rest\Messaging\V1;

use Twilio\Exceptions\TwilioException;
use Twilio\InstanceContext;
use Twilio\ListResource;
use Twilio\Options;
use Twilio\Rest\Messaging\V1\Service\AlphaSenderList;
use Twilio\Rest\Messaging\V1\Service\PhoneNumberList;
use Twilio\Rest\Messaging\V1\Service\ShortCodeList;
use Twilio\Rest\Messaging\V1\Service\UsAppToPersonList;
use Twilio\Rest\Messaging\V1\Service\UsAppToPersonUsecaseList;
use Twilio\Serialize;
use Twilio\Values;
use Twilio\Version;

/**
 * PLEASE NOTE that this class contains beta products that are subject to change. Use them with caution.
 *
 * @property PhoneNumberList $phoneNumbers
 * @property ShortCodeList $shortCodes
 * @property AlphaSenderList $alphaSenders
 * @property UsAppToPersonList $usAppToPerson
 * @property UsAppToPersonUsecaseList $usAppToPersonUsecases
 * @method \Twilio\Rest\Messaging\V1\Service\PhoneNumberContext phoneNumbers(string $sid)
 * @method \Twilio\Rest\Messaging\V1\Service\ShortCodeContext shortCodes(string $sid)
 * @method \Twilio\Rest\Messaging\V1\Service\AlphaSenderContext alphaSenders(string $sid)
 * @method \Twilio\Rest\Messaging\V1\Service\UsAppToPersonContext usAppToPerson(string $sid)
 */
class ServiceContext extends InstanceContext {
    protected $_phoneNumbers;
    protected $_shortCodes;
    protected $_alphaSenders;
    protected $_usAppToPerson;
    protected $_usAppToPersonUsecases;

    /**
     * Initialize the ServiceContext
     *
     * @param Version $version Version that contains the resource
     * @param string $sid The SID that identifies the resource to fetch
     */
    public function __construct(Version $version, $sid) {
        parent::__construct($version);

        // Path Solution
        $this->solution = ['sid' => $sid, ];

        $this->uri = '/Services/' . \rawurlencode($sid) . '';
    }

    /**
     * Update the ServiceInstance
     *
     * @param array|Options $options Optional Arguments
     * @return ServiceInstance Updated ServiceInstance
     * @throws TwilioException When an HTTP error occurs.
     */
    public function update(array $options = []): ServiceInstance {
        $options = new Values($options);

        $data = Values::of([
            'FriendlyName' => $options['friendlyName'],
            'InboundRequestUrl' => $options['inboundRequestUrl'],
            'InboundMethod' => $options['inboundMethod'],
            'FallbackUrl' => $options['fallbackUrl'],
            'FallbackMethod' => $options['fallbackMethod'],
            'StatusCallback' => $options['statusCallback'],
            'StickySender' => Serialize::booleanToString($options['stickySender']),
            'MmsConverter' => Serialize::booleanToString($options['mmsConverter']),
            'SmartEncoding' => Serialize::booleanToString($options['smartEncoding']),
            'ScanMessageContent' => $options['scanMessageContent'],
            'FallbackToLongCode' => Serialize::booleanToString($options['fallbackToLongCode']),
            'AreaCodeGeomatch' => Serialize::booleanToString($options['areaCodeGeomatch']),
            'ValidityPeriod' => $options['validityPeriod'],
            'SynchronousValidation' => Serialize::booleanToString($options['synchronousValidation']),
            'Usecase' => $options['usecase'],
            'UseInboundWebhookOnNumber' => Serialize::booleanToString($options['useInboundWebhookOnNumber']),
        ]);

        $payload = $this->version->update('POST', $this->uri, [], $data);

        return new ServiceInstance($this->version, $payload, $this->solution['sid']);
    }

    /**
     * Fetch the ServiceInstance
     *
     * @return ServiceInstance Fetched ServiceInstance
     * @throws TwilioException When an HTTP error occurs.
     */
    public function fetch(): ServiceInstance {
        $payload = $this->version->fetch('GET', $this->uri);

        return new ServiceInstance($this->version, $payload, $this->solution['sid']);
    }

    /**
     * Delete the ServiceInstance
     *
     * @return bool True if delete succeeds, false otherwise
     * @throws TwilioException When an HTTP error occurs.
     */
    public function delete(): bool {
        return $this->version->delete('DELETE', $this->uri);
    }

    /**
     * Access the phoneNumbers
     */
    protected function getPhoneNumbers(): PhoneNumberList {
        if (!$this->_phoneNumbers) {
            $this->_phoneNumbers = new PhoneNumberList($this->version, $this->solution['sid']);
        }

        return $this->_phoneNumbers;
    }

    /**
     * Access the shortCodes
     */
    protected function getShortCodes(): ShortCodeList {
        if (!$this->_shortCodes) {
            $this->_shortCodes = new ShortCodeList($this->version, $this->solution['sid']);
        }

        return $this->_shortCodes;
    }

    /**
     * Access the alphaSenders
     */
    protected function getAlphaSenders(): AlphaSenderList {
        if (!$this->_alphaSenders) {
            $this->_alphaSenders = new AlphaSenderList($this->version, $this->solution['sid']);
        }

        return $this->_alphaSenders;
    }

    /**
     * Access the usAppToPerson
     */
    protected function getUsAppToPerson(): UsAppToPersonList {
        if (!$this->_usAppToPerson) {
            $this->_usAppToPerson = new UsAppToPersonList($this->version, $this->solution['sid']);
        }

        return $this->_usAppToPerson;
    }

    /**
     * Access the usAppToPersonUsecases
     */
    protected function getUsAppToPersonUsecases(): UsAppToPersonUsecaseList {
        if (!$this->_usAppToPersonUsecases) {
            $this->_usAppToPersonUsecases = new UsAppToPersonUsecaseList(
                $this->version,
                $this->solution['sid']
            );
        }

        return $this->_usAppToPersonUsecases;
    }

    /**
     * Magic getter to lazy load subresources
     *
     * @param string $name Subresource to return
     * @return ListResource The requested subresource
     * @throws TwilioException For unknown subresources
     */
    public function __get(string $name): ListResource {
        if (\property_exists($this, '_' . $name)) {
            $method = 'get' . \ucfirst($name);
            return $this->$method();
        }

        throw new TwilioException('Unknown subresource ' . $name);
    }

    /**
     * Magic caller to get resource contexts
     *
     * @param string $name Resource to return
     * @param array $arguments Context parameters
     * @return InstanceContext The requested resource context
     * @throws TwilioException For unknown resource
     */
    public function __call(string $name, array $arguments): InstanceContext {
        $property = $this->$name;
        if (\method_exists($property, 'getContext')) {
            return \call_user_func_array(array($property, 'getContext'), $arguments);
        }

        throw new TwilioException('Resource does not have a context');
    }

    /**
     * Provide a friendly representation
     *
     * @return string Machine friendly representation
     */
    public function __toString(): string {
        $context = [];
        foreach ($this->solution as $key => $value) {
            $context[] = "$key=$value";
        }
        return '[Twilio.Messaging.V1.ServiceContext ' . \implode(' ', $context) . ']';
    }
}