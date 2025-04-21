<script setup lang="ts">
</script>

<template>
	<div id="app">
		<header>
			<nav>
				<router-link to="/">Home</router-link>
				<router-link to="/favorites">Favoritos</router-link>
			</nav>
		</header>
		<main>
			<router-view />
		</main>
		<footer>
			<p>© 2025 Bruno Rezende</p>
		</footer>
	</div>
</template>

<style scoped>

	header {
		background-color: #1e1e1e;
		padding: 1em;
		position: fixed;
		top: 0;
		left: 0;
		width: 100%;
		z-index: 1000;
		box-shadow: 0 2px 5px rgba(0, 0, 0, 0.5);
	}

	nav {
		display: flex;
		justify-content: center;
		gap: 1em;
	}

	nav a {
		color: #42b883;
		text-decoration: none;
		font-weight: bold;
	}

	nav a.router-link-active {
		text-decoration: underline;
	}

	main {
		flex: 1;
		padding: 20px;
		display: flex;
		flex-direction: column;
		align-items: center;
		gap: 20px;
	}

	footer {
		text-align: center;
	}

	footer p {
		margin: 0;
		font-size: 14px;
	}
</style>