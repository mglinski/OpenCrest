<?php

namespace OpenCrest\Objects;

class ListObject extends Object implements \ArrayAccess
{
    /**
     * Create Next Page from data we received
     *
     * @return ListObject
     */
    public function nextPage()
    {
        $page = $this->endpoint->get($this->endpoint->uri, [
            'query' => 'page=' . $this->values['nextPage']['page']
        ]);

        return $this->endpoint->createObject($page);
    }

    /**
     * Create Previous Page from data we received
     *
     * @return ListObject
     */
    public function previousPage()
    {
        $page = $this->endpoint->get($this->endpoint->uri, [
            'query' => 'page=' . $this->values['previousPage']['page']
        ]);

        return $this->endpoint->createObject($page);
    }

    /**
     * Custom make function, as ListObject is differently processed
     *
     * @param array $data
     * @return Object $this
     */
    public function make($data)
    {
        // We check if items are inside in items array, or directly
        if (isset($data['items'])) {
            $items = $data['items'];
        } else {
            $items = $data;
        }

        $this->values['totalCount'] = isset($data['totalCount']) ? $data['totalCount'] : count($items);
        $this->values['pageCount'] = isset($data['pageCount']) ? $data['pageCount'] : 0;

        $this->parsePages($data);

        $this->values['items'] = [];

        $object = new $this->endpoint->object;

        foreach ($items as $item) {

            // TODO: $types->dogma->attributes->items[0] returns values inside "attribute" array, same with dogma->effects->items[0]
            if (isset($item["attribute"])) {
                $item = $item["attribute"];
            } elseif (isset($item['effect'])) {
                $item = $item["effect"];
            }

            $_item = $object->make($item);

            // Sometimes (like in type->dogma id isnt provided, as items are relations)
            $_item->id = isset($item['id']) ? $item['id'] : null;

            // In rare cases, you can get object listing thro one endpoint, but get specific object thro another
            // This is the case for regions->buy/sellOrders where you get list of orders, but you access those orders
            if ($object->listEndpoint) {
                $_item->setEndpoint(new $object->listEndpoint($this->endpoint->relationId));
            } else {
                $_item->setEndpoint($this->endpoint);
            }

            array_push($this->values['items'], $_item);
        }

        return $this;
    }

    /**
     * We parse next and previous pages depending on date we received
     *
     * @param $pages
     */
    private function parsePages($pages)
    {

        if (isset($pages['next'])) {
            $this->values['nextPage'] = [
                'href' => $pages['next']['href'],
                'page' => ($this->parseUrl($pages['next']['href'])['page'] ? (int)$this->parseUrl($pages['next']['href'])['page'] : null)
            ];
        }
        if (isset($pages['previous'])) {
            $this->values['previousPage'] = [
                'href' => $pages['previous']['href'],
                'page' => (int)(!empty($this->parseUrl($pages['previous']['href'])) ? $this->parseUrl($pages['previous']['href'])['page'] : 1)
            ];
        }
    }

    /**
     * Used when parsing Pages
     *
     * @param $url
     * @return string
     */
    private function parseUrl($url)
    {
        $query = parse_url($url, PHP_URL_QUERY);
        parse_str($query, $value);

        return $value;
    }

    /**
     * Make Object behave as values["items"] array
     *
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->values["items"][] = $value;
        } else {
            $this->values["items"][$offset] = $value;
        }
    }

    /**
     * Make Object behave as values["items"] array
     *
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->values["items"][$offset]);
    }

    /**
     * Make Object behave as values["items"] array
     *
     * @param mixed $offset
     */
    public function offsetUnset($offset)
    {
        unset($this->values["items"][$offset]);
    }

    /**
     * Make Object behave as values["items"] array
     *
     * @param mixed $offset
     * @return null
     */
    public function offsetGet($offset)
    {
        return isset($this->values["items"][$offset]) ? $this->values["items"][$offset] : null;
    }

    /**
     *
     */
    protected function setRelations()
    {
        // No relations
    }
}