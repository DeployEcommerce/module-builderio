define([
    'Magento_Ui/js/form/element/abstract',
    'jquery',
    'uiRegistry'
], function (Abstract, $, registry) {
    'use strict';

    return Abstract.extend({
        initialize: function () {
            this._super();

            /**
             * Converts the flat conditions object from the form into a nested tree structure
             * that Magento's rule model can understand.
             * @param {Object} conditionsObject - The flat object of conditions.
             * @returns {Object|null} - The nested condition tree or null if invalid.
             */
            function convertConditions(conditionsObject) {
                if (!conditionsObject || !conditionsObject['1']) {
                    return null;
                }

                var map = {};
                var result = null;

                Object.keys(conditionsObject).sort().forEach(function(key) {
                    var conditionData = conditionsObject[key];
                    var newNode = {
                        type: conditionData.type,
                        // Set attribute/operator to null if they are missing or empty
                        attribute: conditionData.attribute || null,
                        operator: conditionData.operator || null,
                        // Preserve value if it exists (even if empty string), otherwise null
                        value: typeof conditionData.value !== 'undefined' ? conditionData.value : null,
                        // Explicitly check for '1' to avoid truthiness issues with "0"
                        is_value_processed: conditionData.is_value_processed === '1'
                    };

                    if (conditionData.type && conditionData.type.includes('Combine')) {
                        newNode.aggregator = conditionData.aggregator;
                        newNode.conditions = [];
                    }

                    map[key] = newNode;

                    if (key === '1') {
                        result = newNode;
                    } else {
                        var parts = key.split('--');
                        var parentKey = parts.slice(0, -1).join('--');
                        var parentNode = map[parentKey];

                        if (parentNode && parentNode.conditions) {
                            parentNode.conditions.push(newNode);
                        }
                    }
                });

                return result;
            }

            /**
             * Parses serialized form data into a structured object.
             * Handles array values (e.g., from multiselects).
             * @param {Array} serializedData - Data from jQuery's .serializeArray().
             * @returns {Object} - The parsed data.
             */
            function parseConditions(serializedData) {
                var ruleData = {};

                serializedData.forEach(function (field) {
                    // Match fields like "rule[conditions][1--1][attribute]"
                    var match = field.name.match(/^rule\[conditions\]\[([^\]]+)\]\[([^\]]+)\](\[\])?$/);

                    if (!match) {
                        return;
                    }

                    var key = match[1]; // e.g., "1--1"
                    var attr = match[2]; // e.g., "attribute"
                    var isArray = match[3] === '[]';

                    if (!ruleData[key]) {
                        ruleData[key] = {};
                    }

                    if (isArray) {
                        if (!ruleData[key][attr]) {
                            ruleData[key][attr] = [];
                        }
                        // Ensure it's an array if it was somehow created as a non-array
                        if (!Array.isArray(ruleData[key][attr])) {
                            ruleData[key][attr] = [ruleData[key][attr]];
                        }
                        ruleData[key][attr].push(field.value);
                    } else {
                        ruleData[key][attr] = field.value;
                    }
                });

                return ruleData;
            }

            var providerName = 'builderio_product_collection_form.builderio_product_collection_form_data_source';

            registry.get(providerName, function (provider) {
                provider.save = provider.save.wrap(function (originalSave) {
                    var data = this.get('data');
                    var conditionsData = $('#rule_conditions_fieldset').serializeArray();

                    // Parse the flat form data into a structured object
                    var parsedData = parseConditions(conditionsData);

                    // Convert the structured object into the correct nested tree
                    var finalConditions = convertConditions(parsedData);

                    if (finalConditions) {
                        data.conditions_serialized = JSON.stringify(finalConditions);
                    } else {
                        // Create a default root node if no conditions are specified to clear old values
                        data.conditions_serialized = JSON.stringify({
                            type: 'Magento\\CatalogRule\\Model\\Rule\\Condition\\Combine',
                            aggregator: 'all',
                            value: '1',
                            is_value_processed: false,
                            conditions: []
                        });
                    }

                    this.set('data', data);

                    return originalSave();
                });
            });

            return this;
        }
    });
});
