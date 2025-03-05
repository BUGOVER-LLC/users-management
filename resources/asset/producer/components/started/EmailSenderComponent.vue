<template lang="html">
    <ValidationProvider v-slot="{ errors }" mode="passive" name="email" rules="min:6|email|required|max:150">
        <span>{{ trans('words.email') }}</span>
        <VTextField
            v-model="email"
            autofocus
            color="grey"
            hide-details
            name="email"
            outlined
            placeholder="example@gmail.com"
            type="email"
            :error-messages="errors"
            @input="emailTrigger"
        />
    </ValidationProvider>
</template>

<script lang="ts">
import { validate, ValidationProvider } from 'vee-validate';
import { Component, Emit, Prop, Vue } from 'vue-property-decorator';

import { trans } from '@/producer/utils/StringUtils';

@Component({
    methods: { trans },
    components: { ValidationProvider },
    mixins: [],
})
export default class EmailSenderComponent extends Vue {
    public email: string = '';
    private valid: boolean = false;

    @Prop({ required: false, type: String }) private readonly emailValue;

    created() {
        this.email = this.emailValue;
    }

    emailTrigger() {
        validate(this.email, 'required|email', { name: 'email' }).then((result: any) => {
            if (result && result.valid) {
                this.valid = result.valid ?? false;
            }
            this.emitter();
        });
    }

    @Emit('validEmail')
    emitter() {
        return { email: this.email, valid: this.valid };
    }
}
</script>
