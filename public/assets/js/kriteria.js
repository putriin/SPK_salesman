document.addEventListener("DOMContentLoaded", () => {
  const rows = Array.isArray(window.__KRITERIA_ROWS__)
    ? window.__KRITERIA_ROWS__
    : [];
  const deleteUrl = window.__KRITERIA_DELETE_URL__ || "";

  const state = {
    data: rows,
    filtered: rows,
    page: 1,
    perPage: 5,
    query: "",
  };

  const btnAdd = document.getElementById("btnAdd");
  const modalElement = document.getElementById("kriteriaModal");
  const deleteModalElement = document.getElementById("deleteKriteriaModal");

  const modalInstance = modalElement ? new bootstrap.Modal(modalElement) : null;
  const deleteModalInstance = deleteModalElement
    ? new bootstrap.Modal(deleteModalElement)
    : null;

  const modalTitle = document.getElementById("kriteriaModalLabel");
  const modalForm = document.getElementById("modalForm");

  const formId = document.getElementById("formId");
  const formNama = document.getElementById("formNama");
  const formJenis = document.getElementById("formJenis");
  const formBobot = document.getElementById("formBobot");

  const deleteModalText = document.getElementById("deleteModalText");
  const deleteConfirmBtn = document.getElementById("deleteConfirmBtn");
  const deleteForm = document.getElementById("deleteForm");

  const searchInput = document.getElementById("searchInput");
  const entriesSelect = document.getElementById("entriesSelect");
  const tableBody = document.getElementById("tableBody");
  const tableInfo = document.getElementById("tableInfo");
  const pagination = document.getElementById("pagination");

  let pendingDeleteId = null;

  function openModal(mode = "add", row = null) {
    if (!modalInstance) return;

    if (mode === "edit" && row) {
      if (modalTitle) modalTitle.textContent = "Edit Data";
      if (formId) formId.value = row.id ?? "";
      if (formNama) formNama.value = row.nama ?? "";
      if (formJenis) formJenis.value = String(row.jenis ?? "").toLowerCase();
      if (formBobot) formBobot.value = row.bobot ?? "";
    } else {
      if (modalTitle) modalTitle.textContent = "Add Data";
      modalForm?.reset();
      if (formId) formId.value = "";
    }

    modalInstance.show();
  }

  function openDeleteModal(row) {
    if (!deleteModalInstance || !row) return;

    pendingDeleteId = Number(row.id);

    if (deleteModalText) {
      const nama = row.nama ?? "data ini";
      const jenis = row.jenis ? ` (${row.jenis})` : "";
      deleteModalText.textContent = `Yakin ingin menghapus kriteria "${nama}"${jenis}?`;
    }

    deleteModalInstance.show();
  }

  function applyFilter() {
    const q = state.query.trim().toLowerCase();

    if (!q) {
      state.filtered = [...state.data];
    } else {
      state.filtered = state.data.filter((item) => {
        return (
          String(item.id ?? "")
            .toLowerCase()
            .includes(q) ||
          String(item.nama ?? "")
            .toLowerCase()
            .includes(q) ||
          String(item.jenis ?? "")
            .toLowerCase()
            .includes(q) ||
          String(item.bobot ?? "")
            .toLowerCase()
            .includes(q)
        );
      });
    }

    state.page = 1;
    renderTable();
  }

  function renderTable() {
    if (!tableBody) return;

    const total = state.filtered.length;
    const perPage = Number(state.perPage) || 5;
    const totalPages = Math.max(1, Math.ceil(total / perPage));

    if (state.page > totalPages) state.page = totalPages;
    if (state.page < 1) state.page = 1;

    const start = (state.page - 1) * perPage;
    const end = start + perPage;
    const pageRows = state.filtered.slice(start, end);

    if (pageRows.length === 0) {
      tableBody.innerHTML = `
        <tr>
          <td colspan="5" class="text-center py-4 text-muted">
            Data tidak ditemukan
          </td>
        </tr>
      `;
    } else {
      tableBody.innerHTML = pageRows
        .map((row, index) => {
          return `
            <tr>
              <td class="text-center">${start + index + 1}</td>
              <td>${escapeHtml(row.nama || "")}</td>
              <td class="text-center">${escapeHtml(row.jenis || "")}</td>
              <td class="text-center">${escapeHtml(row.bobot || "")}</td>
              <td class="text-center">
                <div class="d-inline-flex gap-2">
                  <button
                    class="btn btn-sm btn-edit-custom"
                    type="button"
                    data-action="edit"
                    data-id="${escapeHtml(row.id)}"
                  >
                    Edit
                  </button>
                  <button
                    class="btn btn-sm btn-delete-custom"
                    type="button"
                    data-action="delete"
                    data-id="${escapeHtml(row.id)}"
                  >
                    Hapus
                  </button>
                </div>
              </td>
            </tr>
          `;
        })
        .join("");
    }

    if (tableInfo) {
      if (total === 0) {
        tableInfo.textContent = "Showing 0 of 0 entries";
      } else {
        const showingTo = Math.min(end, total);
        tableInfo.textContent = `Showing ${start + 1} to ${showingTo} of ${total} entries`;
      }
    }

    renderPagination(totalPages);
  }

  function renderPagination(totalPages) {
    if (!pagination) return;

    let html = `
      <button class="btn btn-outline-secondary btn-sm" type="button" data-page="prev" ${state.page === 1 ? "disabled" : ""}>
        Previous
      </button>
    `;

    for (let i = 1; i <= totalPages; i += 1) {
      html += `
        <button
          class="btn btn-sm ${i === state.page ? "btn-primary" : "btn-outline-secondary"}"
          type="button"
          data-page="${i}"
        >
          ${i}
        </button>
      `;
    }

    html += `
      <button class="btn btn-outline-secondary btn-sm" type="button" data-page="next" ${state.page === totalPages ? "disabled" : ""}>
        Next
      </button>
    `;

    pagination.innerHTML = html;
  }

  function escapeHtml(value) {
    return String(value)
      .replaceAll("&", "&amp;")
      .replaceAll("<", "&lt;")
      .replaceAll(">", "&gt;")
      .replaceAll('"', "&quot;")
      .replaceAll("'", "&#039;");
  }

  btnAdd?.addEventListener("click", () => {
    openModal("add");
  });

  document.addEventListener("click", (e) => {
    const target = e.target;
    if (!(target instanceof Element)) return;

    const actionBtn = target.closest("[data-action]");
    if (actionBtn) {
      const action = actionBtn.getAttribute("data-action");
      const id = Number(actionBtn.getAttribute("data-id"));
      const row = state.data.find((item) => Number(item.id) === id);

      if (!row) return;

      if (action === "edit") {
        openModal("edit", row);
      } else if (action === "delete") {
        openDeleteModal(row);
      }
      return;
    }

    const pageBtn = target.closest("[data-page]");
    if (pageBtn && pagination?.contains(pageBtn)) {
      const page = pageBtn.getAttribute("data-page");
      const totalPages = Math.max(
        1,
        Math.ceil(state.filtered.length / state.perPage),
      );

      if (page === "prev") {
        state.page = Math.max(1, state.page - 1);
      } else if (page === "next") {
        state.page = Math.min(totalPages, state.page + 1);
      } else {
        state.page = Number(page);
      }

      renderTable();
    }
  });

  searchInput?.addEventListener("input", (e) => {
    state.query = e.target.value || "";
    applyFilter();
  });

  entriesSelect?.addEventListener("change", (e) => {
    state.perPage = Number(e.target.value) || 5;
    state.page = 1;
    renderTable();
  });

  deleteConfirmBtn?.addEventListener("click", () => {
    if (!pendingDeleteId || !deleteForm || !deleteUrl) return;
    deleteForm.action = `${deleteUrl}/${pendingDeleteId}`;
    deleteForm.submit();
  });

  modalElement?.addEventListener("hidden.bs.modal", () => {
    modalForm?.reset();
    if (formId) formId.value = "";
  });

  deleteModalElement?.addEventListener("hidden.bs.modal", () => {
    pendingDeleteId = null;
  });

  renderTable();
});
