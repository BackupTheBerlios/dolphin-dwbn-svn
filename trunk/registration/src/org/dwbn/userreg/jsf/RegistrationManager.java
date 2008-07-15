package org.dwbn.userreg.jsf;

import java.io.IOException;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.Iterator;
import java.util.List;
import java.util.Map;

import javax.faces.component.UIComponent;
import javax.faces.context.FacesContext;

import org.ajax4jsf.model.DataVisitor;
import org.ajax4jsf.model.Range;
import org.ajax4jsf.model.SequenceRange;
import org.ajax4jsf.model.SerializableDataModel;
import org.dwbn.userreg.model.dwbn.Registration;
import org.dwbn.userreg.service.RegistrationService;
import org.dwbn.userreg.service.RegistrationServiceImpl;
import org.richfaces.model.ScrollableTableDataModel;
import org.richfaces.model.SortOrder;
import org.richfaces.model.selection.Selection;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.beans.factory.annotation.Required;
import org.springframework.transaction.annotation.Transactional;

public class RegistrationManager extends ScrollableTableDataModel<Registration> {

	private static final long serialVersionUID = -5050467951543277886L;

	private Integer currentId;
	private List<Integer> wrappedKeys = null;
	private Map<Integer, Registration> wrappedData = new HashMap<Integer, Registration>();

	private RegistrationService service;
	private UIComponent table = null;

	private List<Registration> registrationList = null;
	private boolean detached;
	private Integer rowCount;

	@Autowired(required=true)
	public void setService(RegistrationService service) {
		this.service = service;
	}

	public List<Registration> getRegistrationList() {
		if (registrationList == null) {
			registrationList = service.findAll();
		}
		return registrationList;
	}

	public Selection getSelection() {
		return new Selection() {

			private static final long serialVersionUID = 1L;

			@Override
			public Iterator<Object> getKeys() {
				// TODO Auto-generated method stub
				return null;
			}

			@Override
			public boolean isSelected(Object rowKey) {
				// TODO Auto-generated method stub
				return false;
			}

			@Override
			public int size() {
				// TODO Auto-generated method stub
				return 0;
			}

		};
	}

	public void setTable(UIComponent comp) {
		table = comp;
	}

	public UIComponent getTable() {
		return table;
	}

//	@Override
//	public void update() {
//	}

	@Override
	public Object getRowKey() {
		return currentId;
	}

	@Override
	public void setRowKey(Object key) {
		currentId = (Integer) key;
	}
	
	@Override
	public void walk(FacesContext context, DataVisitor visitor, Range range,
			Object argument) throws IOException {
		int firstRow = ((SequenceRange) range).getFirstRow();
		int numberOfRows = ((SequenceRange) range).getRows();
		if (detached) { // Is this serialized model
			// Here we just ignore current Rage and use whatever data was saved
			// in serialized model.

			// Such approach uses much more getByPk() operations, instead of
			// just one request by range.

			// Concrete case may be different from that, so you can just load
			// data from data provider by range.

			// We are using wrappedKeys list only to preserve actual order of
			// items.
			for (Integer key : wrappedKeys) {
				setRowKey(key);
				visitor.process(context, key, argument);
			}
		} else {
			wrappedKeys = new ArrayList<Integer>();
			for (Registration reg : service.getRegistrationListByRange(
					new Integer(firstRow), numberOfRows)) {
				wrappedKeys.add(reg.getId());
				wrappedData.put(reg.getId(), reg);
				visitor.process(context, reg.getId(), argument);
			}
		}
	}

	@Override
	public int getRowCount() {
		if (rowCount == null) {
			rowCount = new Integer(service.getCount());
			return rowCount.intValue();
		} else {
			return rowCount.intValue();
		}
	}

	@Override
	public Object getRowData() {
		if (currentId == null) {
			return null;
		} else {
			Registration ret = wrappedData.get(currentId);
			if (ret == null) {
				ret = service.find(currentId);
				wrappedData.put(currentId, ret);
				return ret;
			} else {
				return ret;
			}
		}
	}

	@Override
	public int getRowIndex() {
		throw new UnsupportedOperationException();
	}

	@Override
	public Object getWrappedData() {
		throw new UnsupportedOperationException();
	}

	/**
	 * Never called by framework.
	 */
	@Override
	public boolean isRowAvailable() {
		// TODO Auto-generated method stub
		return false;
	}

	@Override
	public void setRowIndex(int rowIndex) {
		
	}

	@Override
	public void setWrappedData(Object data) {
		throw new UnsupportedOperationException();
	}

//	public SerializableDataModel getSerializableModel(Range range) {
//		if (wrappedKeys != null) {
//			detached = true;
//			// Some activity to detach persistent data from wrappedData map may
//			// be taken here.
//			// In that specific case we are doing nothing.
//			return this;
//		} else {
//			return null;
//		}
//	}
	
	public List<Registration> getPending() {
		return service.findAll();
	}
	
	public List<Registration> getVerified() {
		return service.findAll();
	}

	public Integer getNumAccepted() {
		return 3;
	}
	
	public List<Registration> getAccepted() {
		return service.findAll();
	}
	
	@Override
	public List<Registration> loadData(int startRow, int endRow,
			SortOrder sortOrder) {
		return service.getRegistrationListByRange(startRow,endRow);
	}

}
