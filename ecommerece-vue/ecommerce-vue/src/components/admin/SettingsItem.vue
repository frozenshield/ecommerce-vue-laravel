<template>
  <div 
    @click="$emit('click')"
    class="settings-item"
  >
    <div class="icon-container">
      <i :class="iconClass" class="text-blue-600 text-lg"></i>
    </div>
    <div class="content">
      <h4 class="title">{{ title }}</h4>
      <p class="description">{{ description }}</p>
    </div>
    <div class="arrow">
      <i class="pi pi-chevron-right text-gray-400 text-sm"></i>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent, computed } from 'vue'

export default defineComponent({
  name: 'SettingsItem',
  props: {
    icon: {
      type: String,
      required: true
    },
    title: {
      type: String,
      required: true
    },
    description: {
      type: String,
      required: true
    }
  },
  emits: ['click'],
  setup(props) {
    const iconClass = computed(() => {
      // Convert icon name to PrimeIcons class
      if (props.icon.startsWith('pi-')) {
        return `pi ${props.icon}`
      }
      
      // Map common icon names to PrimeIcons
      const iconMap: Record<string, string> = {
        'cog': 'pi-cog',
        'settings': 'pi-cog',
        'envelope': 'pi-envelope',
        'mail': 'pi-envelope',
        'file-text': 'pi-file',
        'file': 'pi-file',
        'share-2': 'pi-share-alt',
        'sliders': 'pi-sliders-h',
        'globe': 'pi-globe',
        'eye': 'pi-eye',
        'code': 'pi-code',
        'database': 'pi-database',
        'activity': 'pi-chart-line',
        'download': 'pi-download',
        'folder': 'pi-folder',
        'map-pin': 'pi-map-marker',
        'repeat': 'pi-refresh',
        'dollar-sign': 'pi-money-bill',
        'package': 'pi-box',
        'search': 'pi-search',
        'tablet': 'pi-tablet',
        'star': 'pi-star',
        'shopping-cart': 'pi-shopping-cart',
        'check-circle': 'pi-check-circle',
        'credit-card': 'pi-credit-card',
        'tag': 'pi-tag',
        'users': 'pi-users',
        'truck': 'pi-truck',
        'grid': 'pi-th-large',
        'server': 'pi-server',
        'bar-chart': 'pi-chart-bar',
        'zap': 'pi-bolt',
        'log-in': 'pi-sign-in',
        'book': 'pi-book',
        'contact': 'pi-phone',
        'captcha': 'pi-key',
        'trending-up': 'pi-chart-line',
        'help-circle': 'pi-question-circle',
        'shield': 'pi-shield'
      }
      
      return `pi ${iconMap[props.icon] || 'pi-cog'}`
    })

    return {
      iconClass
    }
  }
})
</script>

<style scoped>
.settings-item {
  display: flex;
  align-items: flex-start;
  gap: 0.75rem;
  padding: 1rem;
  border-radius: 0.5rem;
  cursor: pointer;
  transition: all 0.2s;
  border: 1px solid transparent;
}

.settings-item:hover {
  background-color: #f9fafb;
  border-color: #e5e7eb;
  box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
}

.icon-container {
  flex-shrink: 0;
  margin-top: 0.25rem;
}

.content {
  flex: 1;
  min-width: 0;
}

.title {
  font-size: 0.875rem;
  font-weight: 500;
  color: #111827;
  margin-bottom: 0.25rem;
}

.description {
  font-size: 0.75rem;
  color: #6b7280;
  line-height: 1.5;
}

.arrow {
  flex-shrink: 0;
  margin-top: 0.25rem;
}
</style>
