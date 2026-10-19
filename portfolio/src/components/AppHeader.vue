<script setup lang="ts">
import { computed } from "vue";

let props = defineProps({
	content: {
		type: String,
		required: true,
	},
	type: {
		type: Number,
		default: 2,
	},
});

const headingTag = computed(() => `h${props.type}`);
</script>

<template>
	<component :is="headingTag">
		{{ content }}
		<slot name="special"></slot>
	</component>
</template>

<style scoped>
h2 {
	width: 100%;
	position: relative;
	font-size: 2rem;
}
h2::before {
	content: "";
	width: calc(100% + calc(1.5 * var(--font-base)));
	height: 2px;
	position: absolute;
	bottom: 0;
	left: calc(-1.5 * var(--font-base));
}
section.hero h2 {
	white-space: nowrap;
}

h3 {
	font-size: 1.5rem;
}

h4 {
	font-size: 0.95rem;
	margin-bottom: calc(0.3 * var(--font-base));
	margin-top: calc(0.8 * var(--font-base));
}

/*Accessibility*/
@media (max-width: 767px) {
	h2 {
		padding: 0 calc(1.5 * var(--font-base));
	}
	section#hero {
		background-color: green;
	}
	section#hero h2 {
		padding: 0;
	}
	h2::before {
		width: calc(100% - calc(2.5 * var(--font-base)));
		left: var(--font-base);
	}
}
</style>
