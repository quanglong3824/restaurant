/**
 * auth.js — 2-Step Login Logic (User -> PIN)
 * Aurora Restaurant
 */

"use strict";

(function () {
  // ── State ──────────────────────────────────────────────
  let pin = "";
  let selectedUsername = "";
  let selectedRole = "";

  // ── DOM refs ────────────────────────────────────────────
  const pinDots = document.querySelectorAll(".pin-dot");
  const pinField = document.getElementById("pinField");
  const usernameField = document.getElementById("usernameField");
  const submitBtn = document.getElementById("submitBtn");
  const loginForm = document.getElementById("loginForm");

  const waiterSection = document.getElementById("waiterSection");
  const pinSection = document.getElementById("pinSection");

  // ── PIN helpers ─────────────────────────────────────────
  function syncDots() {
    pinDots.forEach((dot, i) => {
      dot.classList.toggle("is-filled", i < pin.length);
    });
  }

  function pressKey(value) {
    if (pin.length >= 4) return;
    pin += value;
    syncDots();
    
    if (pin.length === 4) {
      pinField.value = pin;
      // Auto submit when PIN is complete
      setTimeout(() => {
        if (!submitBtn.disabled) {
          submitForm();
        }
      }, 150);
    }
    
    checkReady();
  }

  function deleteKey() {
    if (pin.length === 0) return;
    pin = pin.slice(0, -1);
    pinField.value = pin;
    syncDots();
    checkReady();
  }

  function checkReady() {
    const ready = pin.length === 4 &&
                  selectedUsername.trim().length > 0;
    submitBtn.disabled = !ready;
  }

  function submitForm() {
    submitBtn.classList.add('loading');
    loginForm.submit();
  }

  // ── Step 1: User selection ──────────────────────────────
  function selectUser(el) {
    document.querySelectorAll(".user-chip").forEach((c) => c.classList.remove("is-selected"));
    el.classList.add("is-selected");
    selectedUsername = el.dataset.username;
    selectedRole = el.dataset.role || "waiter";
    usernameField.value = selectedUsername;

    // Reset following steps
    pin = "";
    pinField.value = "";
    syncDots();

    // Show PIN section directly
    pinSection.classList.remove("u-hidden");

    checkReady();
    
    // Scroll smoothly to PIN section
    setTimeout(() => {
      pinSection.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }, 100);
  }

  // ── Bind Events ─────────────────────────────────────────
  document.querySelectorAll(".user-chip").forEach((chip) => {
    chip.addEventListener("click", () => selectUser(chip));
  });

  document.querySelectorAll(".pin-key[data-key]").forEach((btn) => {
    btn.addEventListener("click", () => {
      const val = btn.dataset.key;
      if (val === "del") {
        deleteKey();
      } else {
        pressKey(val);
      }
    });
  });

  // ── Keyboard support (desktop) ──────────────────────────
  document.addEventListener("keydown", (e) => {
    if (pinSection.classList.contains("u-hidden")) return;
    if (e.key >= "0" && e.key <= "9") pressKey(e.key);
    if (e.key === "Backspace") deleteKey();
    if (e.key === "Enter" && !submitBtn.disabled) {
      submitForm();
    }
  });

  // Init
  checkReady();
})();
