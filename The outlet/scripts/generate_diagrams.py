#!/usr/bin/env python3
"""Generate The Outlet design diagram images from DESIGN_DIAGRAMS.md content."""

from pathlib import Path

import matplotlib.pyplot as plt
import matplotlib.patches as mpatches
from matplotlib.patches import FancyBboxPatch, FancyArrowPatch, Circle, Rectangle

ROOT = Path(__file__).resolve().parent.parent
OUT = ROOT / "docs" / "diagrams" / "images"
OUT.mkdir(parents=True, exist_ok=True)

BG = "#f8f9fa"
PRIMARY = "#1a1a1a"
ACCENT = "#2d6a4f"
LIGHT = "#ffffff"
BORDER = "#333333"
MUTED = "#666666"


def save(fig, name: str) -> None:
    path = OUT / name
    fig.savefig(path, dpi=180, bbox_inches="tight", facecolor=BG, edgecolor="none")
    plt.close(fig)
    print(f"Wrote {path}")


def box(ax, x, y, w, h, text, fc=LIGHT, ec=BORDER, fontsize=9, weight="normal", title=None):
    rect = FancyBboxPatch(
        (x, y), w, h,
        boxstyle="round,pad=0.02,rounding_size=0.08",
        facecolor=fc, edgecolor=ec, linewidth=1.5,
    )
    ax.add_patch(rect)
    if title:
        ax.text(x + w / 2, y + h - 0.35, title, ha="center", va="top",
                fontsize=fontsize + 1, fontweight="bold", color=PRIMARY)
        ax.text(x + w / 2, y + h / 2 - 0.15, text, ha="center", va="center",
                fontsize=fontsize, color=MUTED, wrap=True)
    else:
        ax.text(x + w / 2, y + h / 2, text, ha="center", va="center",
                fontsize=fontsize, fontweight=weight, color=PRIMARY)


def arrow(ax, x1, y1, x2, y2, label=None):
    ax.annotate(
        "", xy=(x2, y2), xytext=(x1, y1),
        arrowprops=dict(arrowstyle="-|>", color=BORDER, lw=1.5, shrinkA=2, shrinkB=2),
    )
    if label:
        ax.text((x1 + x2) / 2, (y1 + y2) / 2 + 0.15, label, ha="center", fontsize=8, color=MUTED)


def setup_ax(title: str, w=14, h=10):
    fig, ax = plt.subplots(figsize=(w, h))
    ax.set_xlim(0, 10)
    ax.set_ylim(0, 10)
    ax.set_aspect("equal")
    ax.axis("off")
    fig.patch.set_facecolor(BG)
    ax.set_facecolor(BG)
    ax.text(5, 9.6, title, ha="center", fontsize=16, fontweight="bold", color=PRIMARY)
    return fig, ax


def gen_crc_cards():
    fig, ax = setup_ax("CRC Cards — Class Responsibility Collaborator", w=16, h=12)
    cards = [
        ("User", "Responsibilities:\n• Store user info\n• Manage role & status\n• Handle authentication",
         "Collaborators:\nProduct, Order,\nWishlist, Dispute"),
        ("Product", "Responsibilities:\n• Store product details\n• Manage images\n• Track auth status",
         "Collaborators:\nUser (Seller), Order"),
        ("Order", "Responsibilities:\n• Store order details\n• Track order status\n• Manage escrow",
         "Collaborators:\nUser, Product,\nEscrowTransaction"),
        ("EscrowTransaction", "Responsibilities:\n• Hold payment\n• Release on confirm\n• Handle refunds",
         "Collaborators:\nOrder, User"),
        ("Dispute", "Responsibilities:\n• Store dispute details\n• Track status\n• Link order & users",
         "Collaborators:\nOrder, User, Admin"),
        ("Wishlist", "Responsibilities:\n• Store saved products\n• Manage wishlist items",
         "Collaborators:\nUser, Product"),
    ]
    positions = [(0.3, 6.8), (3.5, 6.8), (6.7, 6.8), (0.3, 3.2), (3.5, 3.2), (6.7, 3.2)]
    for (name, resp, collab), (x, y) in zip(cards, positions):
        box(ax, x, y, 2.8, 2.8, "", fc="#e8f5e9", ec=ACCENT)
        ax.text(x + 1.4, y + 2.55, name, ha="center", fontsize=11, fontweight="bold", color=PRIMARY)
        ax.text(x + 0.15, y + 2.1, resp, ha="left", va="top", fontsize=7.5, color=MUTED)
        ax.text(x + 0.15, y + 0.9, collab, ha="left", va="top", fontsize=7.5, color=ACCENT)
    save(fig, "01_crc_cards.png")


def gen_erd():
    fig, ax = setup_ax("Entity Relationship Diagram (EERD)", w=16, h=11)
    entities = {
        "users": (1, 7.5, "USERS\nuser_id PK\nname, email\nrole, status"),
        "products": (1, 4.5, "PRODUCTS\nproduct_id PK\nseller_id FK\nbrand, price\nauth_status"),
        "orders": (5.5, 6, "ORDERS\norder_id PK\nbuyer_id FK\nseller_id FK\nproduct_id FK\nstatus, amount"),
        "wishlist": (1, 1.5, "WISHLIST\nwishlist_id PK\nuser_id FK\nproduct_id FK"),
        "escrow": (5.5, 2.5, "ESCROW_TRANS\nescrow_id PK\norder_id FK\namount, status"),
        "disputes": (8.5, 4.5, "DISPUTES\ndispute_id PK\norder_id FK\nraised_by FK\nstatus, resolution"),
    }
    for key, (x, y, text) in entities.items():
        box(ax, x, y, 2.2, 1.8, text, fontsize=7.5)
    arrow(ax, 2.1, 7.5, 2.1, 6.3, "1:N")
    arrow(ax, 2.1, 4.5, 2.1, 3.3, "1:N")
    arrow(ax, 3.2, 5.2, 5.5, 6.5, "1:N")
    arrow(ax, 6.6, 6, 6.6, 4.3, "1:1")
    arrow(ax, 7.7, 5.5, 8.5, 5.2, "1:1")
    arrow(ax, 3.2, 7.8, 5.5, 7, "1:N")
    ax.text(5, 0.5, "User→Product 1:N | User→Order 1:N | Order→Escrow 1:1 | Order→Dispute 1:1",
            ha="center", fontsize=9, color=MUTED)
    save(fig, "02_entity_relationship.png")


def gen_context():
    fig, ax = setup_ax("Context Diagram", w=14, h=10)
    box(ax, 3.5, 7, 3, 1.2, "The Outlet System", fc=PRIMARY, ec=PRIMARY, fontsize=11, weight="bold")
    ax.text(5, 7.6, "The Outlet System", ha="center", fontsize=11, fontweight="bold", color=LIGHT)
    for i, (actor, actions) in enumerate([
        ("Buyer", "Browse • Purchase\nWishlist • Dispute"),
        ("Seller", "Upload • Manage\nShip • Sales"),
        ("Admin", "Users • Verify\nDisputes • Analytics"),
    ]):
        x = 0.5 + i * 3.2
        box(ax, x, 4.5, 2.6, 1, actor, fontsize=10, weight="bold")
        box(ax, x, 2.5, 2.6, 1.5, actions, fontsize=8)
        arrow(ax, x + 1.3, 5.5, 4 + i * 0.6, 7)
    save(fig, "03_context_diagram.png")


def gen_dfd():
    fig, ax = setup_ax("Data Flow Diagram — Level 0", w=14, h=10)
    box(ax, 3, 8, 4, 0.9, "The Outlet System", fc=PRIMARY, ec=PRIMARY)
    ax.text(5, 8.45, "The Outlet System", ha="center", fontsize=11, fontweight="bold", color=LIGHT)
    processes = [
        (0.5, 5.5, "1.0\nUser Mgmt"),
        (2.8, 5.5, "2.0\nProduct Mgmt"),
        (5.1, 5.5, "3.0\nPayment & Escrow"),
        (0.5, 2.5, "4.0\nOrder Mgmt"),
        (2.8, 2.5, "5.0\nDispute Mgmt"),
        (5.1, 2.5, "6.0\nAdmin Mgmt"),
    ]
    for x, y, text in processes:
        circle = Circle((x + 0.9, y + 0.6), 0.75, facecolor="#e8f5e9", edgecolor=ACCENT, linewidth=1.5)
        ax.add_patch(circle)
        ax.text(x + 0.9, y + 0.6, text, ha="center", va="center", fontsize=8, color=PRIMARY)
        arrow(ax, x + 0.9, y + 1.35, 5, 8)
    save(fig, "04_dfd_level0.png")


def gen_use_case():
    fig, ax = setup_ax("Use Case Diagram", w=16, h=12)
    box(ax, 0.3, 0.5, 9.4, 9, "", fc="#fafafa", ec=BORDER)
    ax.text(5, 9.2, "The Outlet System", ha="center", fontsize=12, fontweight="bold", color=PRIMARY)
    actors = [("Buyer", 10.5, 7.5), ("Seller", 10.5, 5), ("Admin", 10.5, 2.5)]
    use_cases = {
        "Buyer": [(1.5, 8.2, "Register/Login"), (3.5, 8.2, "Browse Products"), (5.5, 8.2, "Purchase"),
                  (2.5, 6.8, "Wishlist"), (4.5, 6.8, "Confirm Receipt"), (6.5, 6.8, "Raise Dispute")],
        "Seller": [(1.5, 4.5, "Upload Product"), (3.5, 4.5, "Manage Listings"), (5.5, 4.5, "Mark Shipped"),
                   (3.5, 3.2, "View Sales")],
        "Admin": [(1.5, 1.8, "Manage Users"), (3.5, 1.8, "Verify Products"), (5.5, 1.8, "Resolve Disputes"),
                  (4.5, 0.9, "Analytics")],
    }
    for actor, ax_x, ax_y in actors:
        ax.plot(ax_x, ax_y, "o", markersize=14, color=ACCENT)
        ax.text(ax_x + 0.3, ax_y, actor, fontsize=10, fontweight="bold", va="center")
    for actor, cases in use_cases.items():
        ax_x = 10.5
        ax_y = dict(Buyer=7.5, Seller=5, Admin=2.5)[actor]
        for x, y, label in cases:
            ellipse = mpatches.Ellipse((x, y), 1.6, 0.55, facecolor=LIGHT, edgecolor=ACCENT, linewidth=1.2)
            ax.add_patch(ellipse)
            ax.text(x, y, label, ha="center", va="center", fontsize=7)
            arrow(ax, ax_x - 0.2, ax_y, x + 0.8, y)
    save(fig, "05_use_case.png")


def gen_schema():
    fig, ax = setup_ax("Database Schema Overview", w=14, h=9)
    tables = [
        ("users", "user_id, name, email\npassword, role, status"),
        ("products", "product_id, seller_id\nbrand, label_name, size\nprice, auth_status"),
        ("orders", "order_id, buyer_id\nseller_id, product_id\namount, status"),
        ("escrow_transactions", "escrow_id, order_id\namount, status"),
        ("disputes", "dispute_id, order_id\nraised_by, status"),
        ("wishlist", "wishlist_id\nuser_id, product_id"),
    ]
    for i, (name, cols) in enumerate(tables):
        row, col = divmod(i, 3)
        x, y = 0.5 + col * 3.2, 6.5 - row * 3.2
        box(ax, x, y, 2.8, 2.4, cols, title=name, fontsize=8)
    ax.text(5, 0.4, "RBAC • Escrow • Disputes • Wishlist • Product authentication",
            ha="center", fontsize=9, color=MUTED)
    save(fig, "06_database_schema.png")


def main():
    gen_crc_cards()
    gen_erd()
    gen_context()
    gen_dfd()
    gen_use_case()
    gen_schema()
    print(f"\nAll diagrams saved to {OUT}")


if __name__ == "__main__":
    main()
