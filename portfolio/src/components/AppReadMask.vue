<script setup lang="ts">
import { ref, onMounted, onUnmounted } from "vue";

const props = defineProps({
	isActivated: {
		type: Boolean,
		default: false,
	},
});

const topHeight = ref(0);

const handleMouseMove = (event: MouseEvent) => {
	topHeight.value = event.clientY;
};
onMounted(() => {
	window.addEventListener("mousemove", handleMouseMove);
});
onUnmounted(() => {
	window.removeEventListener("mousemove", handleMouseMove);
});
</script>

<template>
	<div class="overlay" :style="`display: ` + (props.isActivated ? `block` : `none`)">
		<div id="top" :style="{ height: `calc(` + topHeight + 'px - calc(2.5 * var(--font-base)))' }"></div>
		<div id="bottom" :style="{ height: `calc( 100vh - ` + topHeight + 'px - calc(2.5 * var(--font-base)))' }"></div>
	</div>
</template>

<style scoped>
div.overlay {
	pointer-events: none;
	z-index: 1;
}
div.overlay > div {
	width: 100%;
	backdrop-filter: blur(calc(0.5 * var(--font-base)));
	position: absolute;
	left: 0;
}
div#top {
	top: 0;
}
div#bottom {
	bottom: 0;
}

/* Reduced Transparency */
html.reducedTransparency div.overlay > div {
	backdrop-filter: none;
	background-color: var(--bg-accessibility);
}
html.reducedTransparency div.overlay > div#top {
	border-bottom: solid calc(0.5 * var(--font-base)) var(--accent-color);
}
html.reducedTransparency div.overlay > div#bottom {
	border-top: solid calc(0.5 * var(--font-base)) var(--accent-color);
}
</style>
