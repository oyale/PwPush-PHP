<?php

namespace oyale;

use Exception;
use JsonSchema\SchemaStorage;
use JsonSchema\Validator;
use JsonSchema\Constraints\Factory;


/**
 *
 */
class JSONValidator
{
    public string $JSON;
    private string $scheme = <<<'JSON'
                            {
                                "definitions": {},
                                "$schema": "http://json-schema.org/draft-07/schema#", 
                                "$id": "https://pwpush.com/schema.json", 
                                "title": "PwPush API JSON Schema", 
                                "type": "object",
                                "required": [
                                    "password"
                                ],
                                "properties": {
                                    "password": {
                                        "$id": "#root/password", 
                                        "title": "Password", 
                                        "type": "object",
                                        "required": [
                                            "payload"
                                        ],
                                        "properties": {
                                            "payload": {
                                                "$id": "#root/password/payload", 
                                                "title": "Payload", 
                                                "type": "string",
                                                "default": "",
                                                "pattern": ".*"
                                            },
                                            "expire_after_days": {
                                                "$id": "#root/password/expire_after_days", 
                                                "title": "Expire_after_days", 
                                                "type": "string",
                                                "default": "",
                                                "pattern": "^[0-9]{1,2}$"
                                            },
                                            "expire_after_views": {
                                                "$id": "#root/password/expire_after_views", 
                                                "title": "Expire_after_views", 
                                                "type": "string",
                                                "default": "",
                                                "pattern": "^[0-9]{1,2}$"
                                            },
                                            "note": {
                                                "$id": "#root/password/note", 
                                                "title": "Note", 
                                                "type": "string",
                                                "default": ""
                                            },
                                            "retrieval_step": {
                                                "$id": "#root/password/retrieval_step", 
                                                "title": "Retrieval_step", 
                                                "type": "string",
                                                "default": "",
                                                "pattern": "^(?:true|false)$"
                                            },
                                            "deletable_by_viewer": {
                                                "$id": "#root/password/deletable_by_viewer", 
                                                "title": "Deletable_by_viewer", 
                                                "type": "string",
                                                "default": "",
                                                "pattern": "^(?:true|false)$"
                                            }
                                        }
                                    }
                            
                                }
                            }
                            JSON;

    private function __construct(string $JSON)
    {
        $this->JSON = $JSON;
    }

    /**
     * @throws Exception
     */
    public static function validate($JSON): bool
    {
        $validator = new self($JSON);
        $schemaStorage = new SchemaStorage();
        $jsonSchemaObject = json_decode($validator->jsonSchema);
        $schemaStorage->addSchema('file://mySchema', $jsonSchemaObject);
        $jsonValidator = new Validator(new Factory($schemaStorage));
        $jsonToValidateObject = json_decode($validator->JSON);
        $jsonValidator->validate($jsonToValidateObject, $jsonSchemaObject);

        if (!$jsonValidator->isValid()) {
            throw new Exception (json_encode($jsonValidator->getErrors));
        }
        return true;
    }
}
