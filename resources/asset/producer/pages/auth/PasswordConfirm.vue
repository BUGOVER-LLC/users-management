<template>
    <div v-if="email" class="auth-wrapper d-flex align-center justify-center pa-4 row">
        <VCard
            class="auth-card pa-4 pt-7 rounded-lg mb-16"
            max-width="600"
            min-width="450"
            outlined
            max-height="500"
            elevation="0"
        >
            <v-card-title class="text-center">
                <span class="text-center">{{ trans('words.login') }}</span>
            </v-card-title>
            <ValidationObserver ref="formPasswordConfirm" v-slot="{ invalid }">
                <v-card-text>
                    <v-form autocomplete="off">
                        <ValidationProvider v-slot="{ errors }" rules="required|max:50|min:3" name="email">
                            <v-text-field
                                placeholder="example@mail.com"
                                :value="email"
                                filled
                                outlined
                                readonly
                                disabled
                                :error-messages="errors"
                            />
                        </ValidationProvider>

                        <ValidationProvider v-slot="{ errors }" rules="required|max:50|min:3" name="password">
                            <v-text-field
                                v-model="password"
                                :placeholder="trans('auth.password_set')"
                                filled
                                outlined
                                type="password"
                                :error-messages="errors"
                            />
                        </ValidationProvider>

                        <div v-if="passwordAccept">
                            <ValidationProvider
                                v-slot="{ errors }"
                                rules="required|max:50|min:3"
                                name="passwordConfirm"
                            >
                                <v-text-field
                                    v-if="passwordAccept"
                                    v-model="passwordConfirm"
                                    :placeholder="trans('auth.repeat_password')"
                                    filled
                                    outlined
                                    :error-messages="errors"
                                />
                            </ValidationProvider>
                        </div>
                    </v-form>
                </v-card-text>

                <v-card-actions>
                    <v-btn block outlined :disabled="invalid" @click="loginByPassword">{{ $t('words.login') }}</v-btn>
                </v-card-actions>
            </ValidationObserver>
        </VCard>
    </div>
</template>

<script lang="ts">
import { extend, ValidationObserver, ValidationProvider } from 'vee-validate';
import { email, max, min, required } from 'vee-validate/dist/rules';
import { Component, Prop, Vue } from 'vue-property-decorator';

import { OnCreated } from '@/producer/common/contracts/OnCreated';
import routesNames from '@/producer/router/routesNames';
import { SendSecretAuth } from '@/producer/services/api/AuthApi';

import { trans } from '../../utils/StringUtils';

extend('required', required);
extend('min', min);
extend('max', max);
extend('email', email);

@Component({
    methods: { trans },
    components: {
        ValidationObserver,
        ValidationProvider,
    },
})
export default class PasswordConfirm extends Vue implements OnCreated {
    @Prop({ required: true }) public passwordAccept: boolean;
    @Prop({ required: true, type: String }) public email: string;

    public password: string = '';
    public passwordConfirm: null | string = null;

    public created() {
        if (!this.email) {
            this.$router.push({ name: routesNames.authIndex });
        }
    }

    public async loginByPassword() {
        try {
            await SendSecretAuth({ email: this.email, password: this.password, passwordConfirm: this.passwordConfirm });
            location.reload();
        } catch (error) {
            console.log(error);
        }
    }
}
</script>
