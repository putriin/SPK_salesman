document.addEventListener("DOMContentLoaded", () => {
  const canvas = document.getElementById("kinerjaChart");
  if (!canvas) return;

  // data dari attribute (sementara)
  let labels = [];
  let values = [];
  try {
    labels = JSON.parse(canvas.dataset.labels || "[]");
    values = JSON.parse(canvas.dataset.values || "[]");
  } catch (e) {
    console.warn("Gagal parse data chart:", e);
  }

  // kalau Chart.js belum kebaca (misal offline), biar halaman tetap aman
  if (typeof Chart === "undefined") {
    const msg = document.createElement("div");
    msg.style.padding = "12px";
    msg.style.border = "1px dashed #e4e7ec";
    msg.style.borderRadius = "12px";
    msg.style.color = "#667085";
    msg.textContent =
      "Chart.js belum termuat. Jika kamu offline, nanti bisa download Chart.js dan taruh di public/assets/js.";
    canvas.replaceWith(msg);
    return;
  }

  new Chart(canvas, {
    type: "bar",
    data: {
      labels,
      datasets: [
        {
          label: "Nilai Preferensi",
          data: values,
          backgroundColor: "rgba(255, 182, 224, 0.85)", // baby pink
          borderColor: "rgba(255, 182, 224, 1)",
          borderWidth: 1,
          borderRadius: 6, // biar lembut kayak contoh
        },
      ],
    },

    options: {
      responsive: true,
      maintainAspectRatio: false,
      scales: {
        y: { beginAtZero: true, suggestedMax: 1 },
      },
      scales: {
        y: {
          beginAtZero: true,
          suggestedMax: 1,
          ticks: { font: { family: "Poppins" } },
        },
        x: {
          ticks: { font: { family: "Poppins" } },
        },
      },

      plugins: {
        legend: {
          display: true,
          position: "top",
          align: "center",
          labels: {
            padding: 20,
            font: { family: "Poppins", size: 12, weight: "500" },
            boxWidth: 30,
          },
        },

        tooltip: {
          callbacks: { label: (ctx) => `Nilai: ${ctx.parsed.y}` },
        },
      },
    },
  });
});
