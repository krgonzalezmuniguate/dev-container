<template>
  <div ref="triggerRef" @mouseenter="showTooltip" @mouseleave="hideTooltip" class="inline-block">
    <slot></slot>
  </div>

  <teleport to="body">
    <transition name="tooltip-fade">
      <div
        v-if="isVisible"
        ref="tooltipRef"
        :style="tooltipStyle"
        :class="[
          'absolute z-50 rounded-md border bg-popover px-3 py-1.5 text-sm text-popover-foreground shadow-md bg-gray-200',
        ]"
      >
        {{ message }}
        <!-- El pico (flecha) -->
        <div
          :class="[
            'absolute w-2 h-2 rotate-45 bg-popover border border-popover bg-gray-200',
            arrowClass
          ]"
        ></div>
      </div>
    </transition>
  </teleport>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted, nextTick } from 'vue'

const props = defineProps({
  message: {
    type: String,
    required: true,
  },
  position: {
    type: String,
    default: 'top', // 'top', 'bottom', 'left', 'right'
    validator: (value) => ['top', 'bottom', 'left', 'right'].includes(value),
  },
})

const isVisible = ref(false)
const triggerRef = ref(null)
const tooltipRef = ref(null)
const tooltipStyle = ref({})

let resizeObserver = null

const showTooltip = async () => {
  isVisible.value = true
  await nextTick() // Asegura que el tooltip se renderice antes de calcular la posición
  calculatePosition()
}

const hideTooltip = () => {
  isVisible.value = false
}

const calculatePosition = () => {
  if (!triggerRef.value || !tooltipRef.value) return

  const triggerRect = triggerRef.value.getBoundingClientRect()
  const tooltipRect = tooltipRef.value.getBoundingClientRect()

  let top = 0
  let left = 0
  const offset = 8 // Distancia entre el disparador y el tooltip

  switch (props.position) {
    case 'top':
      top = triggerRect.top - tooltipRect.height - offset
      left = triggerRect.left + (triggerRect.width / 2) - (tooltipRect.width / 2)
      break
    case 'bottom':
      top = triggerRect.bottom + offset
      left = triggerRect.left + (triggerRect.width / 2) - (tooltipRect.width / 2)
      break
    case 'left':
      top = triggerRect.top + (triggerRect.height / 2) - (tooltipRect.height / 2)
      left = triggerRect.left - tooltipRect.width - offset
      break
    case 'right':
      top = triggerRect.top + (triggerRect.height / 2) - (tooltipRect.height / 2)
      left = triggerRect.right + offset
      break
  }

  // Ajuste básico para mantener el tooltip dentro del viewport
  // Para una solución más robusta, se podría usar una librería como Floating UI
  if (top < 0) top = 0
  if (left < 0) left = 0
  if (top + tooltipRect.height > window.innerHeight) top = window.innerHeight - tooltipRect.height
  if (left + tooltipRect.width > window.innerWidth) left = window.innerWidth - tooltipRect.width

  tooltipStyle.value = {
    top: `${top + window.scrollY}px`, // Añadir scrollY para posicionamiento absoluto relativo al documento
    left: `${left + window.scrollX}px`, // Añadir scrollX para posicionamiento absoluto relativo al documento
  }
}

const arrowClass = computed(() => {
  switch (props.position) {
    case 'top':
      // Flecha en la parte inferior del tooltip, apuntando hacia arriba
      return 'bottom-[-4px] left-1/2 -translate-x-1/2 border-t-0 border-l-0 border-r border-b'
    case 'bottom':
      // Flecha en la parte superior del tooltip, apuntando hacia abajo
      return 'top-[-4px] left-1/2 -translate-x-1/2 border-b-0 border-r-0 border-t border-l'
    case 'left':
      // Flecha en la parte derecha del tooltip, apuntando hacia la izquierda
      return 'right-[-4px] top-1/2 -translate-y-1/2 border-l-0 border-b-0 border-t border-r'
    case 'right':
      // Flecha en la parte izquierda del tooltip, apuntando hacia la derecha
      return 'left-[-4px] top-1/2 -translate-y-1/2 border-r-0 border-t-0 border-b border-l'
    default:
      return ''
  }
})

onMounted(() => {
  // Recalcular posición al redimensionar la ventana
  window.addEventListener('resize', calculatePosition)

  // Usar ResizeObserver para el elemento disparador si su tamaño cambia
  if (triggerRef.value) {
    resizeObserver = new ResizeObserver(() => {
      if (isVisible.value) {
        calculatePosition()
      }
    })
    resizeObserver.observe(triggerRef.value)
  }
})

onUnmounted(() => {
  window.removeEventListener('resize', calculatePosition)
  if (resizeObserver) {
    resizeObserver.disconnect()
  }
});
</script>

<style scoped>
/* Transición personalizada para el tooltip */
.tooltip-fade-enter-active,
.tooltip-fade-leave-active {
  transition: opacity 0.2s ease, transform 0.2s ease;
}

.tooltip-fade-enter-from,
.tooltip-fade-leave-to {
  opacity: 0;
  transform: scale(0.95);
}
</style>
