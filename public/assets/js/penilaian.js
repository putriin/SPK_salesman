(() => {
  const allRows = Array.isArray(window.__PENILAIAN_ROWS__)
    ? window.__PENILAIAN_ROWS__
    : [];

  const entriesSelect = document.getElementById("entriesSelect");
  const searchInput = document.getElementById("searchInput");
  const tableBody = document.getElementById("tableBody");
  const tableInfo = document.getElementById("tableInfo");
  const pagination = document.getElementById("pagination");

  const btnAdd = document.getElementById("btnAdd");

  // Modal Add/Edit
  const modal = document.getElementById("modal");
  modal?.classList.remove("is-open");

  const modalTitle = document.getElementById("modalTitle");
  const modalForm = document.getElementById("modalForm");

  const formId = document.getElementById("formId");
  const formPeriode = document.getElementById("formPeriode");
  const formNama = document.getElementById("formNama");
  const formKriteria = document.getElementById("formKriteria");
  const formJenis = document.getElementById("formJenis");
  const formSkor = document.getElementById("formSkor");

  // Modal Delete
  const deleteModal = document.getElementById("deleteModal");
  deleteModal?.classList.remove("is-open");

  const deleteConfirmBtn = document.getElementById("deleteConfirmBtn");
  const deleteModalText = document.getElementById("deleteModalText");

  let pendingDeleteId = null;

  let state = {
    query: "",
    perPage: Number(entriesSelect?.value || 5),
    page: 1,
    data: allRows,
  };

  function openModal(mode, row = null) {
    if (!modal) return;

    modal.classList.add("is-open");
    modal.setAttribute("aria-hidden", "false");

    if (modalTitle) {
      modalTitle.textContent = mode === "edit" ? "Edit Data" : "Add Data";
    }

    if (formId) formId.value = row?.id ?? "";
    if (formPeriode) formPeriode.value = row?.periode ?? "";
    if (formNama) formNama.value = row?.nama ?? "";
    if (formKriteria) formKriteria.value = row?.kriteria ?? "";
    if (formJenis) formJenis.value = row?.jenis ?? "";
    if (formSkor) formSkor.value = row?.skor ?? "";
  }

  function closeModal() {
    if (!modal) return;

    modal.classList.remove("is-open");
    modal.setAttribute("aria-hidden", "true");

    modalForm?.reset();
    if (formId) formId.value = "";
  }

  function openDeleteModal(row) {
    if (!deleteModal) return;

    pendingDeleteId = Number(row?.id);

    if (deleteModalText) {
      const nama = row?.nama ?? "data ini";
      const kriteria = row?.kriteria ? ` - ${row.kriteria}` : "";
      deleteModalText.textContent = `Yakin ingin menghapus penilaian "${nama}"${kriteria}? Data yang dihapus tidak bisa dikembalikan.`;
    }

    deleteModal.classList.add("is-open");
    deleteModal.setAttribute("aria-hidden", "false");
  }

  function closeDeleteModal() {
    if (!deleteModal) return;

    pendingDeleteId = null;
    deleteModal.classList.remove("is-open");
    deleteModal.setAttribute("aria-hidden", "true");
  }

  function filteredData() {
    const q = (state.query || "").trim().toLowerCase();
    if (!q) return state.data;

    return state.data.filter((r) => {
      const blob =
        `${r.id} ${r.periode} ${r.nama} ${r.kriteria} ${r.jenis} ${r.skor}`.toLowerCase();
      return blob.includes(q);
    });
  }

  function paginate(data) {
    const total = data.length;
    const totalPages = Math.max(1, Math.ceil(total / state.perPage));
    state.page = Math.min(state.page, totalPages);

    const start = (state.page - 1) * state.perPage;
    const end = start + state.perPage;

    return {
      slice: data.slice(start, end),
      total,
      totalPages,
      start,
    };
  }

  function renderPagination(totalPages) {
    if (!pagination) return;

    const pages = [];
    for (let i = 1; i <= totalPages; i++) {
      pages.push(`
        <button class="page-btn ${i === state.page ? "is-active" : ""}" type="button" data-page="${i}">
          ${i}
        </button>
      `);
    }

    pagination.innerHTML = `
      <button class="page-btn" type="button" data-page="prev">Previous</button>
      ${pages.join("")}
      <button class="page-btn" type="button" data-page="next">Next</button>
    `;
  }

  function renderTable() {
    if (!tableBody) return;

    const data = filteredData();
    const { slice, total, totalPages, start } = paginate(data);

    const rowsHtml = slice
      .map((r, idx) => {
        const no = start + idx + 1;
        return `
          <tr>
            <td>${no}</td>
            <td>${r.periode ?? ""}</td>
            <td>${r.nama ?? ""}</td>
            <td>${r.kriteria ?? ""}</td>
            <td>${r.jenis ?? ""}</td>
            <td>${r.skor ?? ""}</td>
            <td>
              <button class="icon-btn icon-btn--edit" type="button" data-action="edit" data-id="${r.id}">✏️</button>
              <button class="icon-btn icon-btn--delete" type="button" data-action="delete" data-id="${r.id}">🗑️</button>
            </td>
          </tr>
        `;
      })
      .join("");

    const minRows = state.perPage;
    const emptyCount = Math.max(0, minRows - slice.length);

    const emptyRows = Array.from(
      { length: emptyCount },
      () => `
      <tr class="empty-row">
        <td>&nbsp;</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
      </tr>
    `,
    ).join("");

    tableBody.innerHTML = rowsHtml + emptyRows;

    const shown = slice.length;
    if (tableInfo) {
      tableInfo.textContent = `Showing ${shown} of ${total} entries`;
    }

    renderPagination(totalPages);
  }

  entriesSelect?.addEventListener("change", () => {
    state.perPage = Number(entriesSelect.value);
    state.page = 1;
    renderTable();
  });

  searchInput?.addEventListener("input", () => {
    state.query = searchInput.value;
    state.page = 1;
    renderTable();
  });

  pagination?.addEventListener("click", (e) => {
    const btn = e.target.closest("button[data-page]");
    if (!btn) return;

    const value = btn.dataset.page;
    const data = filteredData();
    const totalPages = Math.max(1, Math.ceil(data.length / state.perPage));

    if (value === "prev") {
      state.page = Math.max(1, state.page - 1);
    } else if (value === "next") {
      state.page = Math.min(totalPages, state.page + 1);
    } else {
      state.page = Number(value);
    }

    renderTable();
  });

  tableBody?.addEventListener("click", (e) => {
    const btn = e.target.closest("button[data-action]");
    if (!btn) return;

    const action = btn.dataset.action;
    const id = Number(btn.dataset.id);
    const row = state.data.find((r) => Number(r.id) === id);

    if (action === "edit") {
      if (!row) return;
      openModal("edit", row);
      return;
    }

    if (action === "delete") {
      if (!row) return;
      openDeleteModal(row);
    }
  });

  btnAdd?.addEventListener("click", () => openModal("add"));

  modal?.addEventListener("click", (e) => {
    if (e.target?.dataset?.close) {
      closeModal();
    }
  });

  modalForm?.addEventListener("submit", (e) => {
    e.preventDefault();

    const payload = {
      id: formId?.value ? Number(formId.value) : Date.now(),
      periode: formPeriode?.value?.trim() || "",
      nama: formNama?.value?.trim() || "",
      kriteria: formKriteria?.value?.trim() || "",
      jenis: formJenis?.value || "",
      skor: formSkor?.value?.trim() || "",
    };

    if (formId?.value) {
      state.data = state.data.map((r) =>
        Number(r.id) === Number(payload.id) ? payload : r,
      );
    } else {
      state.data = [payload, ...state.data];
    }

    closeModal();
    renderTable();
  });

  deleteModal?.addEventListener("click", (e) => {
    if (e.target?.dataset?.close) {
      closeDeleteModal();
    }
  });

  deleteConfirmBtn?.addEventListener("click", () => {
    if (!pendingDeleteId) return;

    state.data = state.data.filter((r) => Number(r.id) !== pendingDeleteId);

    closeDeleteModal();
    renderTable();
  });

  document.addEventListener("keydown", (e) => {
    if (e.key !== "Escape") return;

    if (modal?.classList.contains("is-open")) closeModal();
    if (deleteModal?.classList.contains("is-open")) closeDeleteModal();
  });

  renderTable();
})();
