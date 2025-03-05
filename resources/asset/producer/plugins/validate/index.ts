import { configure, extend } from 'vee-validate';
import * as rules from 'vee-validate/dist/rules';

import { i18n } from './i18';

configure({
    //@ts-expect-error
    defaultMessage: (field, values) => {
        values._field_ = i18n.t(`${field}`);

        return i18n.t(`validation.${values._rule_}`, values);
    },
});

for (const [rule, validation] of Object.entries(rules)) {
    extend(rule, {
        ...validation,
    });
}
