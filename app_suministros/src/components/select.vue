<template>
  <div class="relative">
    <div class="relative">
      <input
        type="text"
        v-model="searchQuery"
        @focus="isOpen = true"
        @blur="closeOptions"
        @input="filterOptions"
        :placeholder="placeholder"
        class="w-full px-3 py-2 border border-slate-300 rounded-md focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-transparent"
      />

      <button
        v-if="modelValue"
        @click.prevent="clearSelection"
        class="absolute inset-y-0 right-0 flex items-center px-3 text-slate-500 hover:text-red-500"
      >
        &times;
      </button>
    </div>

    <div
      v-if="isOpen && filteredOptions.length > 0"
      class="absolute z-10 w-full mt-1 bg-white border border-slate-300 rounded-md shadow-lg max-h-60 overflow-y-auto"
    >
      <div
        v-for="option in filteredOptions"
        :key="option.id"
        @mousedown.prevent="selectOption(option)"
        class="px-3 py-2 cursor-pointer hover:bg-slate-100"
      >
        {{ option.name }}
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    options: {
      type: Array,
      required: true
    },
    modelValue: {
      type: [String, Number],
      default: ''
    },
    placeholder: {
      type: String,
      default: 'Seleccionar...'
    }
  },
  data() {
    return {
      isOpen: false,
      searchQuery: '',
      filteredOptions: []
    };
  },
  watch: {
    options: {
      handler(newOptions) {
        this.filteredOptions = newOptions;
        this.updateSearchQuery();
      },
      immediate: true
    },
    modelValue: {
      handler() {
        this.updateSearchQuery();
      },
      immediate: true
    }
  },
  methods: {
    filterOptions() {
      if (!this.searchQuery) {
        this.filteredOptions = this.options;
      } else {
        const query = this.searchQuery.toLowerCase();
        this.filteredOptions = this.options.filter(option =>
          option.name.toLowerCase().includes(query)
        );
      }
    },
    selectOption(option) {
      this.$emit('update:modelValue', option.id);
      this.searchQuery = option.name;
      this.isOpen = false;
    },
    closeOptions() {
      setTimeout(() => {
        this.isOpen = false;
      }, 100);
    },
    updateSearchQuery() {
      const selectedOption = this.options.find(opt => opt.id === this.modelValue);
      this.searchQuery = selectedOption ? selectedOption.name : '';
    },
    clearSelection() {
      this.$emit('update:modelValue', '');
      this.searchQuery = '';
      this.isOpen = false;
    }
  }
};
</script>