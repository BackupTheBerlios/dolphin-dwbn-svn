package org.dwbn.userreg.model.dolphin;

// Generated May 12, 2008 11:13:52 PM by Hibernate Tools 3.2.1.GA

import javax.persistence.Column;
import javax.persistence.Entity;
import javax.persistence.GeneratedValue;
import static javax.persistence.GenerationType.IDENTITY;
import javax.persistence.Id;
import javax.persistence.Table;

/**
 * PageCompose generated by hbm2java
 */
@Entity
@Table(name = "PageCompose", catalog = "dolphin")
public class PageCompose implements java.io.Serializable {

	private Integer id;
	private String page;
	private String pageWidth;
	private String desc;
	private String caption;
	private byte column;
	private int order;
	private String func;
	private String content;
	private byte designBox;
	private byte colWidth;
	private String visible;
	private int minWidth;

	public PageCompose() {
	}

	public PageCompose(String page, String pageWidth, String desc,
			String caption, byte column, int order, String func,
			String content, byte designBox, byte colWidth, String visible,
			int minWidth) {
		this.page = page;
		this.pageWidth = pageWidth;
		this.desc = desc;
		this.caption = caption;
		this.column = column;
		this.order = order;
		this.func = func;
		this.content = content;
		this.designBox = designBox;
		this.colWidth = colWidth;
		this.visible = visible;
		this.minWidth = minWidth;
	}

	@Id
	@GeneratedValue(strategy = IDENTITY)
	@Column(name = "ID", unique = true, nullable = false)
	public Integer getId() {
		return this.id;
	}

	public void setId(Integer id) {
		this.id = id;
	}

	@Column(name = "Page", nullable = false)
	public String getPage() {
		return this.page;
	}

	public void setPage(String page) {
		this.page = page;
	}

	@Column(name = "PageWidth", nullable = false, length = 10)
	public String getPageWidth() {
		return this.pageWidth;
	}

	public void setPageWidth(String pageWidth) {
		this.pageWidth = pageWidth;
	}

	@Column(name = "Desc", nullable = false, length = 65535)
	public String getDesc() {
		return this.desc;
	}

	public void setDesc(String desc) {
		this.desc = desc;
	}

	@Column(name = "Caption", nullable = false)
	public String getCaption() {
		return this.caption;
	}

	public void setCaption(String caption) {
		this.caption = caption;
	}

	@Column(name = "Column", nullable = false)
	public byte getColumn() {
		return this.column;
	}

	public void setColumn(byte column) {
		this.column = column;
	}

	@Column(name = "Order", nullable = false)
	public int getOrder() {
		return this.order;
	}

	public void setOrder(int order) {
		this.order = order;
	}

	@Column(name = "Func", nullable = false)
	public String getFunc() {
		return this.func;
	}

	public void setFunc(String func) {
		this.func = func;
	}

	@Column(name = "Content", nullable = false, length = 65535)
	public String getContent() {
		return this.content;
	}

	public void setContent(String content) {
		this.content = content;
	}

	@Column(name = "DesignBox", nullable = false)
	public byte getDesignBox() {
		return this.designBox;
	}

	public void setDesignBox(byte designBox) {
		this.designBox = designBox;
	}

	@Column(name = "ColWidth", nullable = false)
	public byte getColWidth() {
		return this.colWidth;
	}

	public void setColWidth(byte colWidth) {
		this.colWidth = colWidth;
	}

	@Column(name = "Visible", nullable = false, length = 10)
	public String getVisible() {
		return this.visible;
	}

	public void setVisible(String visible) {
		this.visible = visible;
	}

	@Column(name = "MinWidth", nullable = false)
	public int getMinWidth() {
		return this.minWidth;
	}

	public void setMinWidth(int minWidth) {
		this.minWidth = minWidth;
	}

}