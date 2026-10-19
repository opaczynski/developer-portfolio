<script setup lang="ts">
import { useAlerts } from "./useAlerts.ts";
const { alerts, removeAlert } = useAlerts();
</script>

<template>
	<div role="alert" aria-live="assertive" aria-atomic="true" class="sr-only">
		<p v-for="alert in alerts" :key="'sr-' + alert.id">
			{{ alert.message }}
		</p>
	</div>
	<div class="overlay" aria-hidden="true">
		<TransitionGroup name="toast">
			<span v-for="alert in alerts" v-show="!alert.srOnly" :key="alert.id" @click="removeAlert(alert.id)">
				{{ alert.message }}
			</span>
		</TransitionGroup>
	</div>
</template>

<style scoped>
div.overlay {
	width: 100vw;
	height: 100vh;
	padding: calc(1.5 * var(--font-base));
	z-index: 999;
	position: fixed;
	top: 0;
	left: 0;
	display: flex;
	align-items: center;
	justify-content: flex-start;
	flex-direction: column;
	pointer-events: none;
}

span {
	display: flex;
	align-items: center;
	justify-content: center;
	background-color: var(--bg-accessibility);
	border: solid 2px var(--accent-color);
	margin-top: var(--font-base);
	color: var(--text);
	font-size: 1.2rem;
	height: 5rem;
	padding: 0 var(--font-base);
	border-radius: calc(2.5 * var(--font-base));
	pointer-events: auto;
	cursor: pointer;
}

.sr-only {
	position: absolute !important;
	width: 1px !important;
	height: 1px !important;
	padding: 0 !important;
	margin: -1px !important;
	overflow: hidden !important;
	clip: rect(0, 0, 0, 0) !important;
	white-space: nowrap !important;
	border: 0 !important;
}

.toast-enter-from {
	opacity: 0;
}
.toast-leave-to {
	opacity: 0;
}
.toast-leave-active {
	position: absolute;
}

html.noReducedMotion {
	.toast-enter-from {
		transform: translateY(-30px) scale(0.9);
	}
	.toast-leave-to {
		transform: translateY(-20px) scale(0.95);
	}
	.toast-enter-active,
	.toast-leave-active {
		transition: all 0.35s cubic-bezier(0.34, 1.56, 0.64, 1);
	}
	.toast-move {
		transition: transform 0.35s ease;
	}
}
</style>
