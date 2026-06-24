#!/usr/bin/env python3
"""Combine The Outlet design diagram PNGs into a single PDF."""

from pathlib import Path

from fpdf import FPDF
from PIL import Image

ROOT = Path(__file__).resolve().parent.parent
IMAGES = ROOT / "docs" / "diagrams" / "images"
OUT = ROOT / "docs" / "diagrams" / "The Outlet_Design_Diagrams.pdf"

DIAGRAMS = [
    ("01_crc_cards.png", "1. CRC Cards - Class Responsibility Collaborator"),
    ("02_entity_relationship.png", "2. Entity Relationship Diagram (EERD)"),
    ("03_context_diagram.png", "3. Context Diagram"),
    ("04_dfd_level0.png", "4. Data Flow Diagram - Level 0"),
    ("05_use_case.png", "5. Use Case Diagram"),
    ("06_database_schema.png", "6. Database Schema Overview"),
]


class DiagramPDF(FPDF):
    def header(self):
        self.set_font("Helvetica", "B", 10)
        self.set_text_color(100, 100, 100)
        self.cell(0, 8, "The Outlet - Design Diagrams", align="R", new_x="LMARGIN", new_y="NEXT")


def main():
    pdf = DiagramPDF(orientation="L", unit="mm", format="A4")
    pdf.set_auto_page_break(auto=False)
    pdf.set_margins(15, 15, 15)

    # Title page
    pdf.add_page()
    pdf.set_font("Helvetica", "B", 28)
    pdf.set_text_color(26, 26, 26)
    pdf.ln(50)
    pdf.cell(0, 14, "The Outlet", align="C", new_x="LMARGIN", new_y="NEXT")
    pdf.set_font("Helvetica", "", 18)
    pdf.set_text_color(45, 106, 79)
    pdf.cell(0, 12, "Design Diagrams", align="C", new_x="LMARGIN", new_y="NEXT")
    pdf.ln(10)
    pdf.set_font("Helvetica", "", 11)
    pdf.set_text_color(100, 100, 100)
    pdf.cell(0, 8, "C2C Platform for Designer & Streetwear Label Clothing", align="C")

    page_w = 297 - 30  # A4 landscape width minus margins
    page_h = 210 - 30

    for filename, title in DIAGRAMS:
        path = IMAGES / filename
        if not path.exists():
            raise FileNotFoundError(f"Missing diagram: {path}")

        with Image.open(path) as img:
            iw, ih = img.size

        pdf.add_page()
        pdf.set_font("Helvetica", "B", 14)
        pdf.set_text_color(26, 26, 26)
        pdf.cell(0, 10, title, new_x="LMARGIN", new_y="NEXT")
        pdf.ln(4)

        # Scale image to fit remaining page area
        avail_h = page_h - 20
        scale = min(page_w / iw, avail_h / ih)
        w = iw * scale
        h = ih * scale
        x = 15 + (page_w - w) / 2
        y = pdf.get_y()

        pdf.image(str(path), x=x, y=y, w=w, h=h)

    OUT.parent.mkdir(parents=True, exist_ok=True)
    pdf.output(str(OUT))
    print(f"Wrote {OUT}")


if __name__ == "__main__":
    main()
