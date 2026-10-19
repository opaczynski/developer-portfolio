<script setup>
import { computed } from "vue";

const props = defineProps({
	translation: {
		type: Object,
		required: true,
	},
});
</script>

<template>
	<footer>
		<nav>
			<a href="#top">{{ props.translation.toTop }}</a>
		</nav>
	</footer>
</template>

<style scoped>
footer {
	padding: calc(0.5 * var(--font-base)) 0 max(calc(0.5 * var(--font-base)), env(safe-area-inset-bottom));
	margin-top: calc(2.5 * var(--font-base));
}
footer::before {
	top: 0;
}
nav {
	width: max-content;
	margin: auto;
}
</style>
