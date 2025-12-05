<template>
  <Header />
  <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100">
    <div class="container mx-auto px-4 py-12 max-w-4xl">
      <!-- Header -->
      <div class="text-center mb-12">
        <h1 class="text-4xl font-bold text-slate-900 mb-4">Preguntas Frecuentes</h1>
        <p class="text-lg text-slate-600 mb-8">
          Encuentra respuestas rápidas a las preguntas más comunes
        </p>

        <!-- Search Bar -->
        <div class="relative max-w-md mx-auto">
          <SearchIcon class="absolute left-3 top-1/2 transform -translate-y-1/2 text-slate-400 w-4 h-4" />
          <input
            v-model="searchTerm"
            type="text"
            placeholder="Buscar en las preguntas..."
            class="w-full pl-10 pr-4 py-3 border border-slate-200 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
          />
        </div>
      </div>

      <!-- FAQ Accordion -->
      <div class="bg-white rounded-lg shadow-sm border border-slate-200 mb-8">
        <div class="p-6">
          <div v-if="filteredFAQ.length > 0" class="space-y-4">
            <div
              v-for="(item, index) in filteredFAQ"
              :key="item.id"
              class="border-b border-slate-100 last:border-b-0"
            >
              <button
                @click="toggleAccordion(index)"
                class="w-full text-left py-4 flex justify-between items-center hover:text-blue-600 transition-colors duration-200"
              >
                <span class="font-medium text-slate-900 pr-4">{{ item.question }}</span>
                <ChevronDownIcon
                  :class="[
                    'w-5 h-5 text-slate-400 transition-transform duration-200',
                    openItems.includes(index) ? 'transform rotate-180' : ''
                  ]"
                />
              </button>
              <transition
                name="accordion"
                @enter="enter"
                @after-enter="afterEnter"
                @leave="leave"
              >
                <div v-show="openItems.includes(index)" class="overflow-hidden">
                  <div class="pb-4 pr-8">
                    <p class="text-slate-600 leading-relaxed">{{ item.answer }}</p>
                  </div>
                </div>
              </transition>
            </div>
          </div>
          <div v-else class="text-center py-8">
            <p class="text-slate-500">
              No se encontraron preguntas que coincidan con tu búsqueda.
            </p>
          </div>
        </div>
      </div>

      <!-- Contact Support Section -->
      <!-- <div class="bg-white rounded-lg shadow-sm border border-slate-200">
        <div class="text-center p-6 border-b border-slate-100">
          <h3 class="text-xl font-semibold text-slate-900 flex items-center justify-center gap-2 mb-2">
            <MessageCircleIcon class="w-5 h-5" />
            ¿No encontraste lo que buscabas?
          </h3>
          <p class="text-slate-600">Nuestro equipo de soporte está aquí para ayudarte</p>
        </div>
        <div class="p-6 flex flex-col sm:flex-row gap-4 justify-center">
          <button class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-6 rounded-lg flex items-center justify-center gap-2 transition-colors duration-200">
            <MessageCircleIcon class="w-4 h-4" />
            Chat en Vivo
          </button>
          <button class="border border-slate-300 hover:border-slate-400 text-slate-700 font-medium py-3 px-6 rounded-lg flex items-center justify-center gap-2 transition-colors duration-200">
            <MailIcon class="w-4 h-4" />
            Enviar Email
          </button>
        </div>
      </div> -->

      <!-- Quick Stats -->
      <!-- <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
        <div class="text-center">
          <div class="text-2xl font-bold text-slate-900">{"<2min"}</div>
          <div class="text-sm text-slate-600">Tiempo de respuesta promedio</div>
        </div>
        <div class="text-center">
          <div class="text-2xl font-bold text-slate-900">24/7</div>
          <div class="text-sm text-slate-600">Soporte disponible</div>
        </div>
        <div class="text-center">
          <div class="text-2xl font-bold text-slate-900">98%</div>
          <div class="text-sm text-slate-600">Satisfacción del cliente</div>
        </div>
      </div> -->
    </div>
  </div>
  <Top />
</template>

<script setup>
import { ref, computed } from 'vue'
import { SearchIcon, ChevronDownIcon, MessageCircleIcon, MailIcon } from 'lucide-vue-next'
import Header from '@/components/Header.vue'
import Top from '@/components/Top.vue'

const searchTerm = ref('')
const openItems = ref([])

const faqData = [
  {
    id: 'item-1',
    question: '¿Cómo puedo crear una cuenta?',
    answer: 'Para crear una cuenta, haz clic en el botón "Registrarse" en la parte superior derecha de la página. Completa el formulario con tu información personal y verifica tu correo electrónico. El proceso toma menos de 2 minutos.'
  },
  {
    id: 'item-2',
    question: '¿Cuáles son los métodos de pago aceptados?',
    answer: 'Aceptamos todas las tarjetas de crédito principales (Visa, MasterCard, American Express), PayPal, transferencias bancarias y pagos con criptomonedas. Todos los pagos son procesados de forma segura.'
  },
  {
    id: 'item-3',
    question: '¿Puedo cancelar mi suscripción en cualquier momento?',
    answer: 'Sí, puedes cancelar tu suscripción en cualquier momento desde tu panel de usuario. No hay penalizaciones por cancelación anticipada y mantendrás acceso hasta el final de tu período de facturación actual.'
  },
  {
    id: 'item-4',
    question: '¿Ofrecen soporte técnico?',
    answer: 'Ofrecemos soporte técnico 24/7 a través de chat en vivo, email y teléfono. Nuestro equipo de expertos está disponible para ayudarte con cualquier problema técnico o pregunta que puedas tener.'
  },
  {
    id: 'item-5',
    question: '¿Cómo puedo restablecer mi contraseña?',
    answer: 'Para restablecer tu contraseña, haz clic en "¿Olvidaste tu contraseña?" en la página de inicio de sesión. Ingresa tu correo electrónico y recibirás un enlace para crear una nueva contraseña.'
  },
  {
    id: 'item-6',
    question: '¿Hay una versión móvil disponible?',
    answer: 'Sí, tenemos aplicaciones móviles nativas para iOS y Android disponibles en App Store y Google Play Store. También nuestra versión web está completamente optimizada para dispositivos móviles.'
  },
  {
    id: 'item-7',
    question: '¿Cómo funciona la política de reembolsos?',
    answer: 'Ofrecemos una garantía de reembolso de 30 días sin preguntas. Si no estás satisfecho con nuestro servicio, puedes solicitar un reembolso completo dentro de los primeros 30 días de tu suscripción.'
  },
  {
    id: 'item-8',
    question: '¿Mis datos están seguros?',
    answer: 'La seguridad de tus datos es nuestra prioridad. Utilizamos encriptación SSL de 256 bits, cumplimos con GDPR y realizamos auditorías de seguridad regulares. Nunca compartimos tu información personal con terceros.'
  }
]

const filteredFAQ = computed(() => {
  if (!searchTerm.value) return faqData
  
  return faqData.filter(item =>
    item.question.toLowerCase().includes(searchTerm.value.toLowerCase()) ||
    item.answer.toLowerCase().includes(searchTerm.value.toLowerCase())
  )
})

const toggleAccordion = (index) => {
  const itemIndex = openItems.value.indexOf(index)
  if (itemIndex > -1) {
    openItems.value.splice(itemIndex, 1)
  } else {
    openItems.value.push(index)
  }
}

// Animation methods for accordion
const enter = (el) => {
  el.style.height = '0'
  el.offsetHeight // trigger reflow
  el.style.height = el.scrollHeight + 'px'
}

const afterEnter = (el) => {
  el.style.height = 'auto'
}

const leave = (el) => {
  el.style.height = el.scrollHeight + 'px'
  el.offsetHeight // trigger reflow
  el.style.height = '0'
};
</script>

<style scoped>
.accordion-enter-active,
.accordion-leave-active {
  transition: height 0.3s ease;
}

.accordion-enter-from,
.accordion-leave-to {
  height: 0;
}
</style>