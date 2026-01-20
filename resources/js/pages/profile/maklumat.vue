<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import AuthBase from '@/layouts/AuthLayout.vue';
import { login } from '@/routes';
import { store } from '@/routes/register';
import { type BreadcrumbItem } from '@/types';
import { dashboard } from '@/routes';
import { Stepper, StepperDescription, StepperItem, StepperSeparator, StepperTitle, StepperTrigger } from '@/components/ui/stepper'
import { toTypedSchema } from '@vee-validate/zod'
import * as z from 'zod';
import { toast } from 'vue-sonner';
import { Check, Circle, Dot } from 'lucide-vue-next'
import { computed, h, ref } from 'vue'
import { FormControl, FormField, FormItem, FormLabel, FormMessage } from '@/components/ui/form'
import {
  Select,
  SelectContent,
  SelectGroup,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from '@/components/ui/select'
import { useForm } from 'vee-validate'
import { Textarea } from '@/components/ui/textarea'
import { RadioGroup, RadioGroupItem } from '@/components/ui/radio-group'
// import { URL } from 'url';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Maklumat Saya',
        href: dashboard().url,
    },
];



const stepIndex = ref(1)
const steps = [
  {
    step: 1,
    title: 'Personal Information',
    description: 'Provide all details',
  },
  {
    step: 2,
    title: 'Contact Information',
    description: 'Fill contact details',
  },
  {
    step: 3,
    title: 'Review & Confirmation',
    description: 'Upload Photo and fill summary',
  },
]
// // Step validation schemas
// const formSchema = [
//   z.object({
//     fullName: z.string().min(1, 'Full Name is required'),
//     email: z.string().email('Invalid email'),
//   }),
//   z.object({
//     password: z.string().min(6, 'Password must be at least 6 characters'),
//     confirmPassword: z.string().min(6, 'Confirm Password must match'),
//   }).refine(data => data.password === data.confirmPassword, {
//     message: "Passwords don't match",
//     path: ['confirmPassword'],
//   }),
//   z.object({
//     favoriteDrink: z.string().min(1, 'Please select a drink'),
//   }),
// ]

// Create a single form instance
const { handleSubmit, meta, validate, values } = useForm({
//   validationSchema: toTypedSchema(formSchema[stepIndex.value - 1]),
  initialValues: {
    fullName: '',
    email: '',
    password: '',
    confirmPassword: '',
    favoriteDrink: '',
  },
})

// Computed to validate current step only
const isStepValid = computed(() => meta.value.valid)

const nextStep = async () => {
  const valid = await validate()
  if (valid && stepIndex.value < steps.length) stepIndex.value++
}

const prevStep = () => {
  if (stepIndex.value > 1) stepIndex.value--
}

const onSubmit = handleSubmit((formValues) => {
//   console.log('Form submitted:', formValues)
//   alert('Form submitted! Check console.')
})

//UPLOAD PHOTO 

const pictureFile = ref<File | null>(null)

// handle file input change
function onFileChange(event: Event) {
  const target = event.target as HTMLInputElement
  if (target.files && target.files[0]) {
    const file = target.files[0]

    if (!file.type.startsWith('image/')) {
      alert('Please select an image file')
      return
    }

    if (file.size > 2 * 1024 * 1024) {
      alert('File too large (max 2MB)')
      return
    }

    pictureFile.value = file
  }
}
// function handleUpload(event: Event) {
//   const target = event.target as HTMLInputElement
//   const file = target.files?.[0] ?? null
//   componentField.value = file
//   pictureFile.value = file
// }




</script>

<template>
     <Head title="Maklumat Saya" />

    <AppLayout :breadcrumbs="breadcrumbs">

    <div
        class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4
            bg-blue-100 dark:bg-slate-950"
    >
        <div
            class="relative min-h-screen flex-2 rounded-xl border border-sidebar-border/70
                bg-white dark:bg-slate-900 md:min-h-min"
        >
            <Form as="" keep-values @submit.prevent="onSubmit">
                <Stepper v-model="stepIndex" class="block w-full gap-4 mb-6">

                <div class="w-full gap-4 px-6">
                    <div class="flex w-full flex-start">
                    <StepperItem
                        v-for="(step, index) in steps"
                        :key="step.step"
                        :step="step.step"
                        v-slot="{ state }"
                        class="relative flex w-full flex-col items-center justify-center mt-10"
                    >
                        <StepperSeparator
                        v-if="step.step !== steps[steps.length - 1].step"
                        class="absolute left-[calc(50%+20px)] right-[calc(-50%+10px)] top-5 block h-0.5 shrink-0 rounded-full bg-muted group-data-[state=completed]:bg-primary"
                        />
                        <StepperTrigger as-child>
                        <Button
                            :variant="state === 'completed' || state === 'active' ? 'default' : 'outline'"
                            size="icon"
                            class="z-10 rounded-full shrink-0"
                            :class="[state === 'active' && 'ring-2 ring-ring ring-offset-2 ring-offset-background']"
                        >
                            <Check v-if="state === 'completed'" class="size-5" />
                            <Circle v-if="state === 'active'" />
                            <Dot v-if="state === 'inactive'" />
                        </Button>
                        </StepperTrigger>
                        <div class="mt-6 flex flex-col items-center text-center">
                        <StepperTitle :class="[state === 'active' && 'text-primary']" class="text-sm font-semibold transition lg:text-base">
                            {{ step.title }}
                        </StepperTitle>
                        <StepperDescription :class="[state === 'active' && 'text-primary']" class="sr-only text-xs text-muted-foreground transition md:not-sr-only lg:text-sm">
                            {{ step.description }}
                        </StepperDescription>
                        </div>
                    </StepperItem>
                    </div>

                    <!-- Step 1 -->
                    <template v-if="stepIndex === 1">

                    <FormField name="fullName" v-slot="{ componentField }">
                        <FormItem  class="mt-8">
                        <FormLabel>Full Name</FormLabel>
                        <FormControl>
                            <Input type="text" v-bind="componentField" />
                        </FormControl>
                        <FormMessage />
                        </FormItem>
                    </FormField>

                    <FormField name="ic" v-slot="{ componentField }">
                        <FormItem  class="mt-8">
                        <FormLabel>National Identification Number (NIC)</FormLabel>
                        <FormControl>
                            <Input type="text" v-bind="componentField" />
                        </FormControl>
                        <FormMessage />
                        </FormItem>
                    </FormField>

                    <FormField name="gender" v-slot="{ componentField }">
                        <FormItem  class="mt-8">
                        <FormLabel>Gender</FormLabel>
                        <RadioGroup defaultValue='default' className='flex items-center gap-4'>
                        <div className='flex items-center gap-2'>
                            <RadioGroupItem value='male' id='size-default' />
                            <Label htmlFor='size-default'>Male</Label>
                        </div>
                        <div className='flex items-center gap-2'>
                            <RadioGroupItem value='female' id='size-medium' className='size-5 [&_svg]:size-3' />
                            <Label htmlFor='size-medium'>Female</Label>
                        </div>
                        </RadioGroup>
                        </FormItem>
                    </FormField>

                    <FormField name="phone_number" v-slot="{ componentField }">
                        <FormItem  class="mt-7">
                        <FormLabel>Phone Number</FormLabel>
                        <FormControl>
                            <Input type="phone_number" v-bind="componentField" />
                        </FormControl>
                        <FormMessage />
                        </FormItem>
                    </FormField>
                    </template>

                    <!-- Step 2 -->
                    <template v-if="stepIndex === 2">
                    <FormField name="address" v-slot="{ componentField }">
                        <FormItem class="mt-7">
                            <FormLabel>Address</FormLabel>
                            <FormControl>
                            <Textarea
                                v-bind="componentField"
                                placeholder="Type your address here"
                                rows="4"
                            />
                            </FormControl>
                            <FormMessage />
                        </FormItem>
                    </FormField>

                    <FormField name="email" v-slot="{ componentField }">
                        <FormItem  class="mt-7">
                        <FormLabel>Email</FormLabel>
                        <FormControl>
                            <Input type="email" v-bind="componentField" />
                        </FormControl>
                        <FormMessage />
                        </FormItem>
                    </FormField>

                    <FormField name="link" v-slot="{ componentField }">
                        <FormItem  class="mt-7">
                        <FormLabel>Link</FormLabel>
                        <FormControl>
                            <Input type="link" v-bind="componentField" />
                        </FormControl>
                        <FormMessage />
                        </FormItem>
                    </FormField>

                    </template>

                    <!-- Step 3 -->
                    <template v-if="stepIndex === 3">
                        <FormField name="summary" v-slot="{ componentField }">
                            <FormItem class="mt-7">
                                <FormLabel>Summary</FormLabel>
                                <FormControl>
                                <Textarea
                                    v-bind="componentField"
                                    placeholder="Type your summary here"
                                    rows="5"
                                />
                                </FormControl>
                                <FormMessage />
                            </FormItem>
                        </FormField>

                        <FormField name="picture" v-slot="{ componentField }">
                        <FormItem class="mt-7">
                            <FormLabel>Upload Photo</FormLabel>
                            <FormControl>
                            <!-- Input File -->
                            <input
                                 type="file"
                                accept="image/*"
                                onchange="handleUpload"

                                class="border rounded px-2 py-1"
                            />

                            <!-- Preview -->
                            <!-- <img
                                v-if="pictureFile"
                                :src="URL.createObjectURL(pictureFile)"
                                class="mt-4 w-32 h-32 object-cover rounded border"
                                alt="Preview"
                            /> -->
                            </FormControl>
                            <FormMessage />
                        </FormItem>
                        </FormField>




                    </template>

                    <!-- Buttons -->
                    <div class="flex items-center justify-between mt-4">
                        <Button
                            type="button"
                            variant="outline"
                            size="sm"
                            :disabled="stepIndex === 1"
                            @click="prevStep"
                        >
                            Back
                        </Button>
                    <div class="flex items-center gap-3">
                        <Button
                            v-if="stepIndex !== steps.length"
                            type="button"
                            size="sm"
                            :disabled="!isStepValid"
                            @click="nextStep"
                        >
                            Next
                        </Button>
                        <Button
                            v-if="stepIndex === steps.length"
                            size="sm"
                            type="submit"
                        >
                            Submit
                        </Button>

                    </div>
                    </div>
                </div>
                </Stepper>
            </Form>

            <PlaceholderPattern />
        </div>
    </div>

      
    </AppLayout>

</template>
