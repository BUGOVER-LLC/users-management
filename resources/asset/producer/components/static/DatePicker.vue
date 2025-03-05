<template>
    <v-menu
        v-model="picker"
        :close-on-content-click="false"
        :nudge-right="0"
        :nudge-top="30"
        :min-width="pickerWidth"
        offset-y
        transition="scale-transition"
    >
        <template #activator="{}">
            <v-text-field
                v-model="inputValue"
                v-mask="mask"
                :color="color"
                :dense="dense"
                :prepend-inner-icon="inputIcon"
                :name="name"
                :label="label"
                :disabled="disabled"
                outlined
            >
                <template #append style="margin-top: 0">
                    <v-btn
                        :style="!dense ? 'margin-top: -15px; right: -10.6px;' : 'margin-top: -6.2px; right: -10.6px;'"
                        :disabled="disabled"
                        :color="color"
                        :x-large="!dense"
                        depressed
                        @click="picker = !picker"
                    >
                        <v-icon color="primary" large v-text="pickerIcon" />
                    </v-btn>
                </template>
            </v-text-field>
        </template>
        <v-date-picker
            v-model="pickerValue"
            :color="color"
            :max="max"
            :min="min"
            :year-format="format"
            no-title
            @input="picker = false"
        />
    </v-menu>
</template>

<script>
import { VueMaskDirective } from 'v-mask';

export default {
    directives: {
        mask: VueMaskDirective,
    },
    // inject: ['$validator'],

    props: {
        min: {
            default: null,
            required: false,
            type: String,
        },
        max: {
            default: null,
            required: false,
            type: String,
        },
        dense: {
            default: false,
            required: false,
            type: Boolean,
        },
        disabled: {
            default: false,
            required: false,
            type: Boolean,
        },
        value: {
            default: null,
            type: String,
        },
        pickerWidth: {
            default: 290,
            type: [String, Number],
        },
        pickerIcon: {
            default: 'mdi-calendar',
            type: String,
        },
        inputIcon: {
            default: null,
            type: String,
        },
        name: {
            default: null,
            type: String,
        },
        scope: {
            default: null,
            type: String,
        },
        color: {
            default: null,
            type: String,
        },
        label: {
            default: null,
            type: String,
        },
        errorMessages: {
            default: null,
            type: [Array, Object],
        },
        dataVvAs: {
            default: null,
            type: [Array, String],
        },
        rules: {
            default: '',
            type: [String, Object],
        },
        mask: {
            default: '##.##.####',
            type: String,
        },
        format: {
            default: 'dd.mm.yyyy',
            type: String,
        },
    },

    data() {
        return {
            picker: false,
            inputValue: null,
            pickerValue: null,
            mainRules: 'date_format:',
        };
    },

    computed: {
        inputRules() {
            return this.rules
                ? `${this.mainRules}${this.format}` + `|${this.rules}`
                : `${this.mainRules}${this.format}`;
        },
    },

    watch: {
        inputValue() {
            this.setInputValue();
        },
        pickerValue() {
            this.setPickerValue();
        },
        value() {
            this.getValue();
        },
    },

    created() {
        this.getValue();
    },

    methods: {
        setInputValue() {
            if (this.inputValue) {
                const arr = this.inputValue.split('.');
                if (3 === arr.length && arr[2] && 4 === arr[2].length) {
                    arr.reverse();
                    const format = arr.join('-');
                    if (!isNaN(Date.parse(format))) {
                        this.$emit('input', format);
                    }
                }
            } else {
                this.$emit('input', null);
            }
        },

        setPickerValue() {
            this.$emit('input', this.pickerValue);
        },

        getValue() {
            this.pickerValue = this.value;
            if (this.value) {
                const arr = this.value.split('-');
                arr.reverse();
                this.inputValue = arr.join('.');
            }
        },
    },
};
</script>
