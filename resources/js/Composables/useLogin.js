import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'

export function useLogin() {
    const showPassword = ref(false)

    const form = useForm({
        email: '',
        password: '',
        remember: false,
    })

    const togglePassword = () => {
        showPassword.value = !showPassword.value
    }

    const submit = () => {
        form.post(route('login'), {
            onFinish: () => form.reset('password'),
        })
    }

    return {
        form,
        showPassword,
        togglePassword,
        submit,
    }
}
