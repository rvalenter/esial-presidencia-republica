import {ref} from 'vue';

export default function useCPFValidator() {
    const isValid = ref(false);

    function validateCPF(inputCPF) {
        const sanitizedCPF = inputCPF.replace(/[^\d]/g, '');

        if (sanitizedCPF.length !== 11 || /^(\d)\1{10}$/.test(sanitizedCPF)) {
            isValid.value = false;
            return;
        }

        const digit1 = calculateDigit(sanitizedCPF, 9);
        const digit2 = calculateDigit(sanitizedCPF, 10);

        isValid.value =
            parseInt(sanitizedCPF.charAt(9)) === digit1 &&
            parseInt(sanitizedCPF.charAt(10)) === digit2;
    }

    function calculateDigit(cpf, position) {
        const sum = cpf
            .slice(0, position)
            .split('')
            .reduce((acc, digit, index) => {
                return acc + parseInt(digit) * (position + 1 - index);
            }, 0);

        const remainder = 11 - (sum % 11);
        return (remainder === 10 || remainder === 11) ? 0 : remainder;
    }

    return {isValid, validateCPF};
}
